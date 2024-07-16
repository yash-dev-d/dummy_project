<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $books = [
            ['name' => 'Book1'],
            ['name' => 'Book2'],
            ['name' => 'Book3'],
            ['name' => 'Book4'],
            ['name' => 'Book5'],
            ['name' => 'Book6'],
            ['name' => 'Book7'],
            ['name' => 'Book8'],
            ['name' => 'Book9'],
            ['name' => 'Book10'],
        ];

        
        DB::table('books')->insert($books);
    }
}
