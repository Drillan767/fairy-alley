<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use SimpleXLSX;

class UserImportHandler
{
    public function handle(UploadedFile $file)
    {
        $file = SimpleXLSX::parseFile($file);
        if ($file) {
            $rows = collect($file->rows())
                ->map(fn($row) => array_filter($row));

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
            ];

            $mapped = collect($rows->first())->map(fn($label) => $columnMatcher[$label]);

            $rows
                ->forget(0)
                ->each(function ($row) use ($mapped) {
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

                            default: $data[$mapped[$i]] = $value;
                        }

                        $data['password'] = '';
                    }
                    if (!User::firstWhere('email', $data['email'])) {
                        $user = User::create($data);
                        $user->assignRole('subscriber');
                    }
                })
            ;

            return ['success', 'Les utilisateurs ont été importés avec succès'];
        } else {
            return ['error', 'Impossible de charger le fichier'];
        }
    }
}
