<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Log;
use App\AppModel;
use DB;
use Auth;

class UserCon extends Controller
{
	  public function index()
    {
      return view('users.index');  
    }

    public function jsonListStaff(Request $req)
    {
      $search = $req->cari;
      $user = User::select('users.*');
      if($search != null && $user != ""){
        $user = $user->where(function($query) use ($search){
          $query->orWhere('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%');
        });
      }
      $user = $user->paginate(10);
    	return response()->json($user);
    }

    public function form(Request $req)
    {
    	$tb = new User;
    	$id = $req->id;
    	$data = User::find($id);
      
    	return view('users.form')->with('data',$data);
    }

    public function processForm(Request $req)
    {    	
      $app = new AppModel;
    	$id 			= $req->id;
    	$nama 		= $req->name;
    	$telepon 	= $req->telepon;
    	$email		= $req->email;

    	if ($req->password != null && $req->password != "") {
    		$password = bcrypt($req->password);    		
    	}else{
    		$password = null;
    	}
      $foto			= $req->fotoProfile;

    	// upload foto terlebih dahulu    	
    	if ($foto != null && $foto != "") {
    		$foto = $app->uploadFoto($foto);    		
    	}else{
    		$foto = null;
      }

    	$data = ['name'=>$nama,
    					'email'=>$email,
    					'telepon'=>$telepon];

    	if ($id != null && $id != "") {
    		$data["updated_at"] = date('Y-m-d H:i:s');
    		if ($foto != null) {
    			$data["foto"] = $foto;
        }
  
    		if ($password != null) {
    			$data["password"] = $password;
    		}

        DB::table('users')->where('id',$id)->update($data);

    		// response
    		$response["error"] = 0;
    		$response["message"] = "success mengupdate data user";
    		$response["data"] = $data;
    	}else{
    		$data["foto"] = $foto;
    		$data["password"] = $password;
    		$data["created_at"] = date('Y-m-d H:i:s');
    		$data["updated_at"] = date('Y-m-d H:i:s');    		
    		DB::table('users')->insert($data);

    		// response
    		$response["error"] = 0;
    		$response["message"] = "success menambah user data";
    		$response["data"] = $data;
    	}

    	return response()->json($response);
    }

    public function hapusStaff(Request $req)
    {
    	$id = $req->id;
    	DB::table('users')->where('id',$id)->update(['deleted'=>1,'deleted_at'=>date('Y-m-d H:i:s')]);
    	$data = DB::table('users')->where('id',$id)->first();
    	Log::tambahLog("Hapus Staff","Menghapus staff atas nama ".$data->name);

    	$result = ["error"=>0,"message"=>"success"];
    	return $result;
    }

	public function setThemeUser(Request $req)
    {
      $dark = $req->dark;
      DB::table('users')->where('id',Auth::User()->id)->update(['dark_mode'=>$dark]);
      $result = ["error"=>0,"message"=>"success"];
      return json_encode($result);
    }
}
