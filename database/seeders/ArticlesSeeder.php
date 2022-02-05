<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    public function run()
    {
        Article::create(
            [
                'title'=>'Privacy Policy',
                'slug'=>'privacy-policy',
                'content'=>'<p>Your policy text</p>'
            ]
        );
    }
}
