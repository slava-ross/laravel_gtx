<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function cities()
    {
        return $this->belongsToMany('App\City');
        //return $this->belongsToMany('App\City', 'city_comment', 'comment_id', 'city_id'); //related; table; foreignPivotKey; relatedPivotKey.
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
