<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueConstraintsToPlanningTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planning_tasks', function (Blueprint $table) {
            $table->dropUnique(['date', 'start_time']);
            $table->dropUnique(['date', 'end_time']);
            $table->unique(['date', 'start_time', 'deleted_at']);
            $table->unique(['date', 'end_time', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planning_tasks', function (Blueprint $table) {
            $table->dropUnique(['date', 'start_time', 'deleted_at']);
            $table->dropUnique(['date', 'end_time', 'deleted_at']);
            $table->unique(['date', 'start_time']);
            $table->unique(['date', 'end_time']);
        });
    }
}
