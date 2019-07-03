<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('genero')->nullable();
            $table->date('aniversario')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('empresa_id');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
        });

        $empresa = Company::where('nome', 'Messtechnik')->first();

        DB::table('users')->insert(
            array(
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'email' => 'admin@admin',
                    'password' => bcrypt('mstk123'),
                    'empresa_id' => $empresa->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            )
        );
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
