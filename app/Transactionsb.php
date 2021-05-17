<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactionsb extends Model
{
    protected $table = "transaction_sb";
    protected $fillable = [
        'institutionCode',
        'brivaNo',
        'custCode',
        'nama',
        'amount',
        'keterangan',
        'expiredDate',
        'paymentDate',
        'tellerid',
        'no_rek',
        'created_at',
        'updated_at',
        'deleted_at',
        'statusBayar',
        'callback_url',
        'callback_expired'
    ];
    protected $primaryKey = "id";
}
