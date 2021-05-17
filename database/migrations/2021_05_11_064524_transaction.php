<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('institutionCode',10);
            $table->string('brivaNo',20);
            $table->string('custCode',20);
            $table->string('nama',100);
            $table->integer('amount')->default(0);
            $table->string('keterangan')->nullable();
            $table->datetime('expiredDate')->nullable();
            $table->tinyinteger('expired')->default(0)->nullable();
            $table->datetime('paymentDate')->nullable();
            $table->string('tellerid')->nullable();
            $table->string('no_rek')->nullable();
            $table->timestamps();
            $table->datetime('deleted_at')->nullable();
            $table->enum('statusBayar',['Y','N'])->default('N');
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
    }
}
