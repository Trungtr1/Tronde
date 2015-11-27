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
			
			$action = $request->get('action');						
			
			$user = Session::get('user');
			
			if($action=='delete')
			{
				$this->delete_folder($request->get('folder_id'));
			}
			
			$viewData['user']=$user;
			
			$viewData['folder'] = DB::table('folders')->where('id',$id)->get();						
			
			$viewData['children'] = DB::table('folders')->where('parent',$id)->where('categories',0)->get();
			
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
			
			$viewData['files'] = DB::table('folders')->where('parent',$id)->where('categories',1)->get();

			return view('folder')->with('data',$viewData);
		}
		
		public function create_folder(Request $request){
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			$inputData = array(
								'name' 		=> $request['name'],
								'parent'	=> $request['id'],
								'level'		=> $request['level']+1,
								'date'		=> date('Y-m-d'),
								'share'		=> 0
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
					return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Thêm mới thành công'));
				}else{
					return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
				}
				
			} else {
			
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
			
			}
		}
		
		public function delete_folder($id)
		{
			if (DB::table('folders')->where('id', $id)->delete()) {
			
			return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Đã xóa thành công'));
			
			} else {
			
				return \Redirect::back()->with('responseData', array('statusCode' => 2, 'message' => 'Chưa xóa được, vui lòng thử lại'));
			
			}
		}
	}