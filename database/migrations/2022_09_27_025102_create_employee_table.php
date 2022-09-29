<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('team_id')->constrained('teams')->onUpdate('cascade');  //->onDelete('cascade')
            $table->string('email', 128);
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('password',64);
            $table->char('gender',1);
            $table->datetime('birthday');
            $table->string('address',256);
            $table->string('avatar', 128);
            $table->integer('salary');
            $table->char('position', 1);
            $table->char('status', 1);
            $table->char('type_of_work', 1);
            $table->integer('ins_id');
            $table->integer('upd_id')->nullable();
            $table->dateTime('ins_datetime', 0 );
            $table->dateTime('upd_datetime', 0 )->nullable();
            $table->char('del_flag', 1)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
