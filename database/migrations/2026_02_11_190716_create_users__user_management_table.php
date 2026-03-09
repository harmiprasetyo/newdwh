<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Str;


class CreateUsersUserManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Users_UserManagement', function (Blueprint $table) {
            $table->uuid('userid')->primary();
            $table->string('username')->unique();
            $table->string('user_password');
            $table->string('user_fullname');
            $table->string('user_group');
            $table->string('user_organization');
            $table->string('user_organization_code');
            $table->enum('is_healthcare',['1','0'])->default('0');
            $table->enum('is_removable',['1','0'])->default('0');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Users_UserManagement');
    }
}
