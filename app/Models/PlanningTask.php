<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningTask extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'name', 'date', 'start_time', 'end_time', 'description'];

    protected $dates = [
        'start_time',
        'end_time'
    ];
}
