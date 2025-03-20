<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavedSearch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'criteria' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
