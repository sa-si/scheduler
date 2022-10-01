<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropPlanningTaskIdFromPlanningTaskTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planning_task_tags', function (Blueprint $table) {
              DB::statement('alter table planning_task_tags drop FOREIGN KEY planning_task_tags_planning_task_id_foreign;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planning_task_tags', function (Blueprint $table) {
            //
        });
    }
}
