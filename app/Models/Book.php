<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Book extends Model
{

    use HasFactory;
    protected $table = 'books'; //table name

    protected $fillable = ['name']; 

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class, 'shelf_has_books', 'book_id', 'shelf_id');
    }
}
