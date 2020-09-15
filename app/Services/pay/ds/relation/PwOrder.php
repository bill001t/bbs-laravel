<?php

namespace App\Services\pay\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = '_pay_order';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'order_no', 'price', 'number', 'state', 'payemail', 'paymethod', 'paytype', 'buy', 'created_userid', 'created_time', 'extra_1', 'extra_2'];
	protected $guarded = [];

}