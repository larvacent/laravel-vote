<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('source');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('client_ip',45)->nullable()->index();
            $table->boolean('value');
            $table->timestamp('created_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_rating');
    }
}
