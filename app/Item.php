<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description','qrcode','laboratory_id', 'imgItem', 'encryptedImgName','extensionImg',
    ];


    protected $hidden = [
        'encryptedImgName','extensionImg',
    ];

}
