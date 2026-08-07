<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1],
            ['name' => 'Video Editor', 'icon' => 'video_settings', 'sort' => 2],
            ['name' => 'Desain', 'icon' => 'palette', 'sort' => 3],
            ['name' => 'Musik', 'icon' => 'music_note', 'sort' => 4],
            ['name' => 'Streaming', 'icon' => 'tv', 'sort' => 5],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
