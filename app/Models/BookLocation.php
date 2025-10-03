<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookLocation extends Model
{
    protected $fillable = [
        'book_id',
        'rack_number',
        'shelf_number',
        'section',
        'notes'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}