<?php

namespace App\Services\link\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLinkType extends Model
{
    protected $table = '_link_type';
    protected $primaryKey = 'typeid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['typeid', 'typename', 'vieworder'];
    protected $guarded = [];

    public function Link()
    {
        return $this->belongsToMany(PwLink::class, PwLinkRelation::class, 'typeid', 'lid');
    }

}