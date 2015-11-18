<?php namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Session;
use File;


	class FolderController extends Controller {
		
		public function index(Request $request)		
		{
			$id = $request->get('id');
			
			$user = Session::get('user');
			
			$viewData['user']=$user;
			
			$viewData['folder'] = DB::table('folders')->where('id',$id)->get();						
			
			$viewData['children'] = DB::table('folders')->where('parent',$id)->get();
			
			$n = $viewData['folder'][0]['level'];
			
			$parent = $viewData['folder'][0]['parent'];
			
			if($n>1)
			{
				for($i=1;$i<$n;$i++)
				{
					$data_parent = DB::table('folders')->where('id',$parent)->get();
					$viewData['parent'][$n-$i] = $data_parent[0];
					$parent = $data_parent[0]['parent'];
				}
			}

			return view('folder')->with('data',$viewData);
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