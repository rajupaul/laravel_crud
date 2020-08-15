<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded=[];

   public function tags(){
   	return $this->belongsToMany('App\Tag');
   }

   public function user(){
   	return $this->belongsTo('App\User');
   }

   public function category(){
   	return $this->belongsTo('App\Category');
   }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function getTagsIdArray(){
     $id_array=[];
     
     if(count($this->tags)){

      foreach ($this->tags as $tag) {
       $id_array[]=$tag->id;
      }

     }

     return $id_array;
    }
}
