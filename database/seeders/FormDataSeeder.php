<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\FormData;

class FormDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                "target" => "moz.com",
                "exclude_targets" => json_encode([
                    "semrush.com"
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                "target" => "ahrefs.com",
                "exclude_targets" => json_encode([
                    "semrush.com"
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        FormData::insert($items);
    }
}
