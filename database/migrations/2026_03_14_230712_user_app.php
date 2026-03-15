<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('userapp', function (Blueprint $table){
            $table->id('userId')->autoIncrement();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('userName')->unique();
            $table->string('userFullname');
            $table->unsignedBigInteger('userGroupId')->foreignId()->references('group_id')->on('usergroups')->onDelete('Cascade');
             $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('userapp');
    }
}
