<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Image;

class AppModel extends Model
{
    public function nextCode($primary, $table){
	    $result = DB::table($table)->select($primary)->orderBy($primary,'desc')->first();
	    $kode = 0;
	    if(is_null($result) ){
	      $kode = 0;
	    }
	    else{
	      $kode = $result->$primary;

	    }
	    $kode = $kode+1;
	    $kodeString = $kode . "";
	    /*while(1){
	      if(strlen($kodeString)<5){
	        $kodeString = "0".$kodeString;
	      }
	      else{break;}
	    }
	    $kodeString = "F".$kodeString;
	    */
	    return $kodeString;
	  }

	  public function uploadFoto($foto){
		  	if ($foto == null || $foto == "") {
		  		return null;
		  	}
	      $extension = "";
	      $extension = $foto->getClientOriginalExtension();
	      $filename = date('YmdHis').rand(0,9999);
	      if ($extension != 'png' && $extension != 'jpg') {
	        $filename = $filename.".png";
	      }else{
	        $filename = $filename.".".$extension;
	      }
	      try{
	        $image = $foto;
	        // upload ke thumbnail
	        $destinationPath = 'uploadgambar';	  	  	
	        $img = Image::make($image->getRealPath());
	        if ($extension == 'jpg' || $extension == 'png') {
	          $img = $img->resize(500, 500, function ($constraint) {
	            $constraint->aspectRatio();
	          });        
	        }
	        $img->save($destinationPath.'/'.$filename);              
	         
	      }catch(\Exception $exception){
	         $destinationPath = 'uploadgambar';
	         $foto->move($destinationPath,$filename); 
	      }
	      // $destinationPath = 'uploadgambar';
	      // $foto->move($destinationPath,$filename); 

	      return $filename;
	    }
}
