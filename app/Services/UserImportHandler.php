<?php

namespace App\Services;

use App\Models\{Lesson, Media, Page, Service, User};
use Illuminate\Http\UploadedFile;
use SimpleXLSX;

class UserImportHandler
{
    public function handle(UploadedFile $file): array
    {
        $total = 0;
        $errors = [];
        $createdServices = [];
        $lessons = Lesson::all();
        $users = User::all();
        $services = null;
        $file = SimpleXLSX::parseFile($file);

        if ($file) {
            $rows = collect($file->rows())
                ->map(function ($row) {
                    unset($row[0]);
                    return array_filter($row);
                });

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

            // $userColumns[$label])

            $mapped = [];
            foreach ($labels as $i => $label) {
                if (array_key_exists($label, $userColumns)) {
                    $mapped[$i] = $userColumns[$label];
                }
            }

            foreach($rows as $key => $row) {
                if ($key === 0) {
                    $createdServices = $this->handleMissingServices($row);
                    $services = Service::all();
                }

                if ($key !== 0) {
                    dd($row);
                }
            }

            $rows
                ->forget(0)
                ->each(function ($row) use ($users, $mapped, &$errors, &$total, $lessons) {
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
                        $errors[] = "L'email {$data['email']}' est déjà pris.";
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
                $createdServices,
                $errors,
            ];

        } else {
            return [null, ['Impossible de charger le fichier'], null];
        }
    }

    private function handleMissingServices(array $row): array
    {
        $page = Page::first(['id']);
        $services = Service::all();
        $createdServices = [];

        $serviceList = array_slice($row, 12);
        foreach($serviceList as $item) {
            $found = $services->firstWhere('title', $item);
            if (!$found) {
                $service = Service::create([
                    'title' => $item,
                    'description' => "Service créé pour \"$item\".",
                    'page_id' => $page->id,
                    'order' => $services->count() + 1,
                ]);

                $banner = new Media([
                    'title' => $item,
                    'url' => "https://via.placeholder.com/2000x500?text=Banniere+pour+\"$item\"",
                ]);
                $service->banner()->save($banner);

                $thumbnail = new Media([
                    'title' => $item,
                    'url' => "https://via.placeholder.com/2000x500?text=Vignette+pour+\"$item\"",
                ]);

                $service->thumbnail()->save($thumbnail);

                $createdServices[] = $service->title;
            }
        }

        return $createdServices;
    }
}
