<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class movieTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'release_date',
        'overview',
        'poster_path',
    ];
}
