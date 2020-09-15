<?php

namespace App\Services\link\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLink extends Model
{
    protected $table = '_link';
    protected $primaryKey = 'lid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['lid', 'vieworder', 'name', 'url', 'descrip', 'logo', 'iflogo', 'ifcheck', 'contact'];
    protected $guarded = [];

    public function LinkType(){
        return $this->belongsToMany(PwLinkType::class, PwLinkRelation::class, 'lid', 'typeid');
    }

}