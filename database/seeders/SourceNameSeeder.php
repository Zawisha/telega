<?php

namespace Database\Seeders;

use App\Models\SourceName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['name' => 'telegram'],
            ['name' => 'vk'],
        ];

        foreach ($posts as $post) {
            SourceName::create($post);
        }
    }
}
