<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        //constructor e authentication er api middleware diye dicci tar mane user authenticated hote hobe first e.
        //ei line ta kete dile localhost/home e login charai access korte partam.

        //evabe amar localhost/api/users API ke save korlam. ekhn keu oi url e giye user er info ar pabena.
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Database theke user tule return korbo jate table e pore show korate pari
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //server side validation starts here
        $this->validate($request,[
            "name" => "required|string|max:191",
            "email" => "required|string|email|max:191|unique:users", //email should be unique in USERS table in db
            "password" => "required|string|min:5",
        ]);

        //server side validation ends here

        //form e ja ja input dibo sob return korbe and save korbe ei api ta
        return User::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'type'=> $request['type'],
            'bio'=> $request['bio'],
            'photo'=> $request['photo'],
            'password'=> Hash::make($request['password'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function profile()
    {
        //Profile er info fetch kore anlam database theke
        $user = auth('api')->user();//je user logged in sei only tar info/profile update korte parbe.
        //tai auth('api') use korlam jate logged in user er info pete pari.

        return $user;

        //return ["message" => "Profile updated successfully"];
    }
    public function updateProfile(Request $request)
    {
        //Profile er info display korabo ei method diye
        $user =  auth('api')->user();

        //return $request->photo;

        //server side validation starts here
         $this->validate($request,[
            "name" => "required|string|max:191",
            "email" => "required|string|email|max:191|unique:users,email,". $user->id,
            //It means email is required and unique but not for the current user.
            "password" => "sometimes|min:5", //password user change korteo pare nao pare
        ]);
        //server side validation ends here

        /* Photo upload code starts here */

        //Request $request er moddhe form er data gula ashbe.. Ar $user variable e database er user info ashbe

        $currentPhoto = $user->photo;//database er photo column theke astece

        if($request->photo != $currentPhoto) //jodi new photo select kore taholei only upload korbo. echara update korar dorkar nai photo
        {
            //jodi user photo upload kore tahole server e pathay dibo direct

            //image name unique chacci. tai time() function use korlam caz time always unique

            //$imageName = time(). '.' . explode('/',explode(':', substr($request->photo, 0 ,
             //strpos($request->photo,';')))[1])[1]; //image er extension pawar jonno explode korlam.
            $imageName = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            //Now Image Intervention package use kore local directory te image ta save korbo
            \Image::make($request->photo)->save(public_path('img/profile/').$imageName);

            /*
                now new picture upload kora hole ager profile picture delete kore dibo.
                ager profile pic database e store kora. ekhono update kora hoynai. so $currentPhoto var e ace.
                new uploaded pic $request->photo te ace.
            */

            $previousProfilePhoto = public_path('img/profile/').$currentPhoto; //returns img/profile/image_name.extension
            if(file_exists($previousProfilePhoto)){
                @unlink($previousProfilePhoto);
            }


            //now update the picture name
            $request->merge(['photo' => $imageName]);

        }

        /* Photo upload code ends here */

        if(!empty($request->password)){
            //password jeta dibe otake bcrypt kora lagbe then database e update korte parbo
            $request->merge(['password' => Hash::make($request['password'])]);//password ke bcrypt korlam hash use kore

        }

        //now update the other info and store that into database
        $user->update($request->all());
        return ["message" => "Profile successfully updated."];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update users
        $user = User::findOrFail($id);

        //update korar age validate kore nibo new input thik ace kina
        //server side validation starts here
        $this->validate($request,[
            "name" => "required|string|max:191",
            "email" => "required|string|email|max:191|unique:users,email,". $user->id, //email should be unique in USERS table in db
            "password" => "sometimes|min:5", //password user change korteo pare nao pare
        ]);

        $user->update($request->all());

        return ["message" => "User updated successfully"];//ei message ta request successful hole console e dekhte parbo
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //User er data delete korbo. Users model ke ekdom upore import kore nici

        //first find the user
        $user = User::findOrFail($id);

        //then delete the user
        $user->delete();

        return ["message" => "User deleted successfully"];//ei message ta request successful hole console e dekhte parbo
    }
}