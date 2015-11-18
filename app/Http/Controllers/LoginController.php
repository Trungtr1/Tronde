<?php namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Session;

	class LoginController extends Controller {
		
		public function index()
		{
			return view('pages.login');
		}
		
	}