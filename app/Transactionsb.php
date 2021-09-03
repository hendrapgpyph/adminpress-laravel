<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactionsb extends Model
{
    use SoftDeletes;
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
        'created_by',
        'updated_at',
        'deleted_at',
        'statusBayar',
        'callback_url',
        'callback_expired',
    ];
    protected $primaryKey = "id";
}
