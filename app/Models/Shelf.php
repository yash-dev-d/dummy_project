<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Shelf extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];

    
    protected $table = 'shelf';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }
    public function books()
    {
        return $this->belongsToMany(Book::class, 'shelf_has_books', 'shelf_id', 'book_id');
    }
}
