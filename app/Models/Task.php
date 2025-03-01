<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{ 
    use HasFactory;


    protected $fillable = [
        'task_detail',
        'task_date_time',
        'task_created_by',
        'task_status',
    ]; 


    protected $casts = [
        'task_date_time' => 'datetime', 
    ];

   
}
