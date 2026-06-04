<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (LegalPageContentTemplates::pages() as $data) {
            LegalPage::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'summary' => $data['summary'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'is_active' => true,
                    'sort_order' => $data['sort_order'],
                    'published_at' => now(),
                ],
            );
        }
    }
}
