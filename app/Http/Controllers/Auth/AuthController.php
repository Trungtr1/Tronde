<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

	public function login()
	{
		return view('pages.login');
	}
	
	protected function authenticate(Request $request)
	{

		$loginData = array(
			'email'	=>	$request->input('email'),
			'password'	=>	$request->input('password')
		);
		
				
		if (Auth::attempt($loginData)) {
            
			$user = Auth::user()->toArray();
			
			Session::put('user', $user);

			return redirect()->intended('user');
			
        } else {
			return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Thông tin đăng nhập không chính xác'));
			
		}
	}
	
	protected function logout()
	{
		Auth::logout();
		return redirect('/login');
	}
}
