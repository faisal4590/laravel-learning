<?php namespace App\Http\Controllers;

class HomeController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //constructor e auth middleware diye dicci tar mane user authenticated hote hobe first e.
        //ei line ta kete dile localhost/home e login charai access korte partam.
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('home');
    }
}