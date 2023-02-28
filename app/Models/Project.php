<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlanningTask;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function planningTask()
    {
        return $this->hasMany(PlanningTask::class);
    }
}
