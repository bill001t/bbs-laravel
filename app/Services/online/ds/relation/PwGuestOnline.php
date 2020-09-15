<?php

namespace App\Services\online\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwGuestOnline extends Model
{
	protected $table = '_online_guest';
	protected $primaryKey = 'ip';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['ip', 'created_time', 'modify_time','tid', 'fid', 'request'];
	protected $guarded = [];

}