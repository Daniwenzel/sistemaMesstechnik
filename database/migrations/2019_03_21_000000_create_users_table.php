<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//use Illuminate\Support\Facades\DB;
//use Messtechnik\User;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('USERS', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('genero')->nullable();
            $table->date('aniversario')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('cliente_codigo');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('cliente_codigo')
                ->references('CODIGO')
                ->on('CLIENTE');
        });

       // $empresa = DB::select('select * from cliente where razaosocial=?', ['TESTE']);

//        dd($empresa[0]->codigo);

//        $usuario = new User([
//            'id' => 1,
//            'name' => 'Admin',
//            'email' => 'admin@admin',
//            'password' => bcrypt('mstk123'),
//            'cliente_codigo' => 1,
//            'created_at' => now(),
//            'updated_at' => now()
//        ]);
//        $user->save();
    }

    public function down()
    {
        Schema::dropIfExists('USERS');
    }
}
