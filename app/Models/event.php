<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event extends Model
{



    use HasFactory;

    public function calendar()
    {
        return $this->belongsTo(calendar::class);
    }

    protected $fillable = ['title', 'start_date', 'end_date', 'description', 'color', 'textColor', 'user_id'];

}
