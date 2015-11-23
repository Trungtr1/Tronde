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
										
			$viewData['tests'] = DB::table('tests')->where('categories',1)->get();
			
			return view('user')->with('data',$viewData);
		}
		
		public function create_folder(Request $request){
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			if(isset($request['folder_name']))
			{
				$inputData = array(
								'name' 		=> $request['folder_name'],
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
										'role'		=> 3
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
			}else{
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
		
		public function mixed(Request $request){			
			
			$user=Session::get('user');

			$folder = DB::table('folders');
			
			if(isset($request['choosetest']))
			{				
				$questions = array();
		
				foreach($request['choosetest'] as $value)
				{
					$get_questions = DB::table('questions')->where('folder_id',$value)->get();
					foreach($get_questions as $ge)
					{
						$questions[] = $ge['id'];
					}					
				}
				
				if(count($questions)>=$request['number_questions'])
				{
					$tests = array();
					
					$random_questions = array_rand($questions,$request['number_questions']);
					
					$data_questions = array();										
					
					foreach($random_questions as $value)
					{
						$data_questions[] = $questions[$value];
					}										
					
					for($i=0;$i<$request['number_tests'];$i++)
					{
						$tests[] = $this->troncauhoi($data_questions);
					}
					
					DB::table('tests')->update(array('categories' => 0));
					
					foreach($tests as $key1=>$value1)
					{
						$number_test = $key1+1;
						
						$inputData1 = array(
								'name' 		=> "Đề ".$number_test,
								'date'		=> date('Y-m-d'),
							);
						
						if(DB::table('tests')->insert($inputData1))							
						{
							$test_id = DB::getPdo()->lastInsertId();
						
							foreach($value1 as $value2)
							{
								$get_answers = DB::table('answers')->where('question_id',$value2)->get();
								
								$answers = array();
								
								foreach($get_answers as $value3)
								{
									$answers[] = $value3['id'];
								}
								
								$data_answers = $this->troncauhoi($answers);
								
								if($get_answers)
								{
									$inputData2 = array(
													'question_id' 		=> $value2,
													'test_id'		=> $test_id,
													);
								
									if(DB::table('test_questions')->insert($inputData2))
									{
										$test_question_id = DB::getPdo()->lastInsertId();
										
										foreach($data_answers as $key2=>$value4)
										{
											$inputData3 = array(
														'test_question_id' 	=> $test_question_id,
														'answer_id'			=> $value4,
														'order'				=> $key2,
														'test_id'			=> $test_id,
													);
											DB::table('test_answers')->insert($inputData3);											
										}
									}
								}																
							}
						}						
					}
				}
				
				return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Tạo đề thành công'));
				
			}else{
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Yêu cầu chọn thư mục muốn lấy câu hỏi.'));
			}
		}
		
		public function troncauhoi($data)				
		{			
			shuffle($data);
			
			return $data;
		}
	}