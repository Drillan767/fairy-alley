<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use SimpleXLSX;

class UserImportHandler
{
    private array $columnMatcher = [
        'Nom' => 'firstname',
        'Prénom' => 'lastname',
        'Email' => 'email',
        'Adresse' => 'address1',
        'Hommes / Femmes' => 'gender',
        'Autre' => 'other_data',
        'Remarques admin' => 'other_data',
    ];

    public function handle(UploadedFile $file)
    {
        $result = [];
        $file = SimpleXLSX::parseFile($file);
        // regex address: /(.*) (\d{5}) (.*)$/i
        if ($file) {
            $rows = collect($file->rows())
                ->map(function($row) {
                    return array_filter($row);
                });

            dd($rows);
        } else {
            return redirect()->back()->with('error', 'Impossible de charger le fichier');
        }
    }
}
