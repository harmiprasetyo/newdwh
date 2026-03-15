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
        Schema::create('Users_Data', function (Blueprint $table) {
            $table->id('UserId');
            $table->string('UserName')->unique();
            $table->string('UserPassword');
            $table->string('UserFullName');
            $table->string('UserGroupId');
            $table->string('UserOrganization');
            $table->string('UserOrganizationCode');
            $table->enum('is_Healthcare',['1','0'])->default('0');
            $table->enum('is_Removable',['1','0'])->default('0');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('update_at')->useCurrent()->useCurrentOnUpdate();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Users_Data');
    }
}
