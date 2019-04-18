<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteAggregateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_aggregate_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('source');
            $table->unsignedInteger('likes');
            $table->unsignedInteger('dislikes');
            $table->decimal('rating', 3, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_aggregate_rating');
    }
}
