<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableTransactionSb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_sb', function (Blueprint $table) {
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
            $table->enum('status_callback',['success','fail'])->nullable()->default(null);
            $table->string('callback_url')->nullable()->default(null);
            $table->string('callback_expired')->nullable()->default(null);
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
