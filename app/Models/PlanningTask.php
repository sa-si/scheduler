<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Tag;

class PlanningTask extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'name', 'date', 'start_time', 'end_time', 'description'];

    protected $dates = [
        'start_time',
        'end_time'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // public function tag()
    // {
    //     return $this->belongsToMany('App\Tag');
    // }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($planningTask) {
    //         $planningTask->PlanningTaskTag()->delete();
    //     });
    // }
}
