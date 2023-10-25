<?php

namespace Database\Seeders;

use App\Models\FeedbackCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeedbackCategory::insert([
            ['name' => 'Bug Report'],
            ['name' => 'Feature Request'],
            ['name' => 'Improvement'],
        ]);
    }
}
