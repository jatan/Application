<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{

    public function categoryText($len){
    	return $len;
        // return $this ->accounts()->get()->where('hidden_flag',0);
    }
}
