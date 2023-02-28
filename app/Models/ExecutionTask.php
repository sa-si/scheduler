<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExecutionTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'project_id', 'name', 'date', 'start_time', 'end_time', 'description'];

    protected $dates = [
        'start_time',
        'end_time'
    ];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
