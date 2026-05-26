<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolItem;

class ToolItemSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            // Programming Languages row
            ['row_label' => 'Programming Languages', 'name' => 'PHP'],
            ['row_label' => 'Programming Languages', 'name' => 'Laravel'],
            ['row_label' => 'Programming Languages', 'name' => 'JavaScript'],
            ['row_label' => 'Programming Languages', 'name' => 'Vue.js'],
            ['row_label' => 'Programming Languages', 'name' => 'Tailwind CSS'],
            ['row_label' => 'Programming Languages', 'name' => 'Alpine.js'],

            // Editing Tools row
            ['row_label' => 'Editing Tools', 'name' => 'Blender'],
            ['row_label' => 'Editing Tools', 'name' => 'Illustrator'],
            ['row_label' => 'Editing Tools', 'name' => 'After Effects'],
            ['row_label' => 'Editing Tools', 'name' => 'Premiere'],
            ['row_label' => 'Editing Tools', 'name' => 'Photoshop'],
            ['row_label' => 'Editing Tools', 'name' => 'CapCut'],

            // General Tools row
            ['row_label' => 'General Tools', 'name' => 'Git'],
            ['row_label' => 'General Tools', 'name' => 'Figma'],
            ['row_label' => 'General Tools', 'name' => 'Docker'],
            ['row_label' => 'General Tools', 'name' => 'VScode'],
            ['row_label' => 'General Tools', 'name' => 'Jira'],
            ['row_label' => 'General Tools', 'name' => 'Notion'],
            ['row_label' => 'General Tools', 'name' => 'FigJam'],
            ['row_label' => 'General Tools', 'name' => 'Word'],
            ['row_label' => 'General Tools', 'name' => 'Excel'],
            ['row_label' => 'General Tools', 'name' => 'PowerPoint'],
            ['row_label' => 'General Tools', 'name' => 'Canva'],
        ];

        foreach ($tools as $i => $tool) {
            ToolItem::create(array_merge($tool, ['sort_order' => $i]));
        }
    }
}
