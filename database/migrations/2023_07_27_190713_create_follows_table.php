<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{

    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id("id_follows");

            $table->unsignedBigInteger("id_followed");
            $table->unsignedBigInteger("id_follower");
            $table->foreign("id_followed")->references("id")->on("users");
            $table->foreign("id_follower")->references("id")->on("users");

            $table->boolean("friends");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
