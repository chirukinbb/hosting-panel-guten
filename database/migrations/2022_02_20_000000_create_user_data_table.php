<?php

class CreateUserDataTable extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::create('user_data',function (\Illuminate\Database\Schema\Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('avatar_path',100);
            $table->string('public_name',100)->nullable();
            $table->text('biography')->nullable();
            $table->softDeletes();

            $table->foreign('user_id')->on('users')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::dropIfExists('user_data');
    }
}
