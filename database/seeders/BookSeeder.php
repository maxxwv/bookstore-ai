<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
			'title' => 'Neuromancer',
			'author_id' => 1,
			'description' => 'The best book ever written',
			'cover_photo' => null,
			'quantity' => 13,
		]);
		Book::create([
			'title' => 'Killer Swell',
			'author_id' => 1,
			'description' => 'Fletch, but he surfs',
			'cover_photo' => null,
			'quantity' => 0
		]);
    }
}
