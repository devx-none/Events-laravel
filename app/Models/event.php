<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event extends Model
{

    protected $fillable = ['title', 'start_date', 'end_date','description','color','textColor','user_id'];

    use HasFactory;
}