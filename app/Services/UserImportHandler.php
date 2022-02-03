<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Models\{Lesson, Service, Subscription, User};
use Illuminate\Http\UploadedFile;
use SimpleXLSX;

class UserImportHandler
{
    private int $total = 0;
    private array $errors = [];

    public function handle(UploadedFile $file): array
    {
        $file = SimpleXLSX::parseFile($file);
        $lessons = Lesson::all('id', 'ref');
        $services = Service::all();

        if ($file) {
            $rows = collect($file->rows())
                ->map(function ($row) {
                    unset($row[0]);
                    return array_filter($row);
                });

            $servicesFound = array_slice($rows->first(), 12, count($rows->first()), true);
            $labels = $rows->first();

            $userColumns = [
                'Nom' => 'lastname',
                'Prénom' => 'firstname',
                'Portable' => 'phone',
                'Fixe' => 'phone_fix',
                'Pro' => 'pro',
                'Email' => 'email',
                'Adresse' => 'address1',
                'Hommes / Femmes' => 'gender',
                'Remarques admin' => 'other_data',
                'Role' => 'role',
            ];

            $mapped = [];
            foreach ($labels as $i => $label) {
                if (array_key_exists($label, $userColumns)) {
                    $mapped[$i] = $userColumns[$label];
                }
            }

            $rows->forget(0);

            foreach($rows as $row) {
                $data = [];
                foreach($row as $i => $value) {
                    if ($i < 11) {
                        switch ($mapped[$i]) {
                            case 'address1':
                                $matches = null;
                                preg_match('/(.*) (\d{5}) (.*)$/misU', $value, $matches);
                                if (!empty($matches)) {
                                    list(, $address1, $zipcode, $city) = $matches;
                                    $data['address1'] = trim($address1);
                                    $data['zipcode'] = trim($zipcode);
                                    $data['city'] = trim($city);
                                }
                                break;

                            case 'phone_fix':
                                if (isset($data['phone'])) {
                                    $data['phone'] = $data['phone'] .= " / $value";
                                } else {
                                    $data['phone'] = $value;
                                }
                                break;

                            case 'role':
                                $data['role'] = $value;
                                break;

                            default: $data[$mapped[$i]] = $value;
                        }
                    } elseif ($i === 11) {
                        $lesson = $lessons->firstWhere('ref', trim(preg_replace('/\s+/', ' ', $value)));
                        if (!$lesson) {
                            $data['lesson_id'] = null;
                            $this->errors[$value] = "Le cours dont la référence est \"$value\" n'a pas été trouvé.";
                        } else {
                            $data['lesson_id'] = $lesson->id;
                        }

                    } elseif ($i > 11) {
                        if ($value !== '') {
                            $service = $services->firstWhere('ref', $servicesFound[($i + 1)]);
                            if ($service) {
                                $data['services'][] = $service->id;
                            } else {
                                if (!array_key_exists($service, $this->errors)) {
                                    $this->errors[$service] = "Le service \"{$servicesFound[($i + 1)]}\" n'a pas été trouvé.";
                                }
                            }
                        }
                    }

                    $data['password'] = '';
                }

                $this->createUser($data);
            }

            return [
                "$this->total utilisateurs ont été importés.",
                $this->errors,
            ];

        } else {
            return [null, ['Impossible de charger le fichier']];
        }
    }

    private function createUser($data)
    {
        $roleList = config('roles');
        $roles = collect($roleList)->mapWithKeys(function ($role, $i) {
            return [$role['display'] => $i];
        });

        if (User::where('email', $data['email'])->count()) {
            $this->errors[] = "L'email {$data['email']}' est déjà pris.";
        } else {
            $user = new User();

            $fields = [
                'firstname',
                'lastname',
                'email',
                'lesson_id',
                'password',
                'phone',
                'pro',
                'address1',
                'zipcode',
                'city',
                'gender',
                'other_data'
            ];

            foreach($fields as $field) {
                if (isset($data[$field])) {
                    $user->$field = $data[$field];
                }
            }

            $user->save();
            $user->assignRole($roles[$data['role']]);

            if (isset($data['services'])) {
                $user->suggestions()->sync($data['services']);
            }

            if (in_array($user->role, ['guest', 'presubscribed', 'subscriber', 'substitute', 'archived', 'administrator']) && isset($data['lesson_id'])) {
                $subscription = new Subscription();
                $subscription->user_id = $user->id;
                $subscription->lesson_id = $user->lesson_id;
                $subscription->status = $user->hasRole('guest') ? Subscription::PENDING : Subscription::VALIDATED;
                $subscription->selected_time = $data['hour'] ?? '';
                $subscription->save();
            }

            $this->total++;

        }
    }
}
