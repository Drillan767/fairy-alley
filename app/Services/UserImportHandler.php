<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use SimpleXLSX;

class UserImportHandler
{
    public function handle(UploadedFile $file): array
    {
        $total = 0;
        $errors = [];
        $lessons = Lesson::all();
        $file = SimpleXLSX::parseFile($file);
        if ($file) {
            $rows = collect($file->rows())
                ->map(function ($row) {
                    unset($row[0]);
                    array_filter($row);
                    return $row;
                });

            $columnMatcher = [
                'Nom' => 'lastname',
                'Prénom' => 'firstname',
                'Portable' => 'phone',
                'Fixe' => 'phone_fix',
                'Pro' => 'pro',
                'Email' => 'email',
                'Adresse' => 'address1',
                'Hommes / Femmes' => 'gender',
                'Autre' => 'other_data',
                'Remarques admin' => 'other_data_admin',
                'type' => 'type',
                'Role' => 'role',
            ];

            $mapped = collect($rows->first())->map(fn($label) => $columnMatcher[$label]);

            $rows
                ->forget(0)
                ->each(function ($row) use ($mapped, &$errors, &$total, $lessons) {
                    $data = [];
                    foreach($row as $i => $value) {
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

                            case 'other_data_admin':
                                if (isset($data['other_data'])) {
                                    $data['other_data'] = $data['other_data'] .= $value;
                                } else {
                                    $data['other_data'] = $value;
                                }
                                break;

                            case 'role':
                                $data['ref'] = $value;
                                    break;

                            default: $data[$mapped[$i]] = $value;
                        }

                        $data['password'] = '';
                    }

                    if (User::firstWhere('email', $data['email'])) {
                        $errors[] = "L'email {$data['email']}' est déjà prit.";
                    } else {
                        $lesson_id = $lessons->where('ref', $data['ref'])->first()?->id;
                        if ($lesson_id) {
                            $data['lesson_id'] = $lesson_id;
                        } else {
                            $errors[] = "Impossible de trouver un cours dont la référence est \"{$data['ref']}\" pour {$data['email']}";
                        }
                        unset($data['ref']);
                        $user = User::create($data);
                        $user->assignRole('subscriber');
                        $total++;
                    }
                })
            ;

            return [
                "$total utilisateurs ont été importés.",
                $errors,
            ];

        } else {
            return [null, ['Impossible de charger le fichier']];
        }
    }
}
