<?php namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Session;
use File;

	class UserController extends Controller {
		
		public function index()
		{
			$user = Session::get('user');
			
			$viewData['user']=$user;
			
			$viewData['folders'] = DB::table('groups')
										->join('folders','folders.id','=','groups.folder_id')
										->where('groups.user_id',$user['id'])
										->where('folders.level','1')
										->where('folders.share','0')
										->get();
			$viewData['groups'] = DB::table('groups')
										->join('folders','folders.id','=','groups.folder_id')
										->where('groups.user_id',$user['id'])
										->where('folders.level','1')
										->where('folders.share','1')
										->get();
			
			return view('user')->with('data',$viewData);
		}
		
		public function create_folder(Request $request){
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			$inputData = array(
								'name' 		=> $request['name'],
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
									'role'		=> 1
								);
								
				if(DB::table('groups')->insert($inputData))
				{
					return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => '1 thư mục mới đã được tạo'));
				}else{
					return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
				}
				
			} else {
			
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
			
			}
		}
		
		public function create_group(Request $request){
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			$inputData = array(
								'name' 		=> $request['group_name'],
								'parent'	=> null,
								'level'		=> 1,
								'date'		=> date('Y-m-d'),
								'share'		=> 1
							);
			
			if ($folder->insert($inputData)) {
				
				$folder_id = DB::getPdo()->lastInsertId();
				
				$inputData = array(
									'user_id' 	=> $user['id'],
									'folder_id'	=> $folder_id,
									'role'		=> 3
								);
								
				if(DB::table('groups')->insert($inputData))
				{
					return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Tạo nhóm thành công'));
				}else{
					return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
				}
				
			} else {
			
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
			
			}
		}
	}