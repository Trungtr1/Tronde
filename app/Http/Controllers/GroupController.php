<?php namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Session;
use File;


	class GroupController extends Controller {
		
		public function index(Request $request)		
		{
			$id = $request->get('id');
			
			$user = Session::get('user');
			
			$viewData['user']=$user;
			
			$viewData['group'] = DB::table('folders')->where('id',$id)->get();						
			
			$viewData['children'] = DB::table('folders')->where('parent',$id)->get();

			return view('group')->with('data',$viewData);
		}
		
		public function create_folder(Request $request){
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			$inputData = array(
								'name' 		=> $request['name'],
								'keyword'	=> null,
								'parent'	=> null,
								'level'		=> 1,
								'date'		=> date('Y-m-d'),
								'share'		=> 0
							);
			
			if ($folder->insert($inputData)) {
				
				$folder_id = DB::getPdo()->lastInsertId();
				
				$inputData = array(
									'user_id' 	=> $user['id'],
									'folder_id'	=> $folder_id,
								);
								
				if(DB::table('groups')->insert($inputData))
				{
					return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Thêm mới thành công'));
				}else{
					return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
				}
				
			} else {
			
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
			
			}
		}
	}