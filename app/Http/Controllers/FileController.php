<?php namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Session;
use File;


	class FileController extends Controller {
		
		public function index(Request $request)		
		{
			$id = $request->get('id');
			
			$user = Session::get('user');
			
			$viewData['user']=$user;
			
			$viewData['file'] = DB::table('folders')->where('id',$id)->get();

			$viewData['parent'] = DB::table('folders')->where('id',$viewData['file'][0]['parent'])->get();
			
			$viewData['questions'] = DB::table('questions')->where('folder_id',$id)->get();
			
			$answers = DB::table('answers')->where('folder_id',$id)->get();			
			
			foreach($answers as $value)
			{
				$viewData['answers'][$value['question_id']][] = $value;
			}
			
			
			return view('file')->with('data',$viewData);
		}
		
		/*public function create_file(Request $request)
		{
			$user=Session::get('user');
			
			$inputData = array(
								'name' 		=> $request['file_name'],
								'date'		=> date('Y-m-d'),
								'folder_id' => $request['folder_id'],
								'like'		=> 0,
							);
			
			if (DB::table('files')->insert($inputData)) {
				
				return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Thêm mới thành công'));

			} else {
			
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'));
			
			}
		}*/
		
		public function create_file(Request $request)
		{
			$user=Session::get('user');
			
			$inputData = array(
								'name' 		=> $request['file_name'],
								'parent'	=> $request['folder_id'],
								'level'		=> $request['folder_level']+1,
								'date'		=> date('Y-m-d'),
								'categories'=> 1,
								'share'		=> 0
							);
			
			if (DB::table('folders')->insert($inputData)) {
				
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
		
		public function mixQuestion(Request $request)
		{
			if($request['choosetest']){
				$questions = array();
			
				$get_questions = DB::table('questions')->where('folder_id',$request['choosetest'])->get();
				
				foreach($get_questions as $ge)
				{
					$questions[] = $ge['id'];
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
								'school'	=> $request['school'],
								'title'		=> $request['title'],
								'subject'	=> $request['subject'],
								'time'		=> $request['time'],
								'date'		=> date('Y-m-d'),
								'categories'=> 1,
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
					return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Tạo đề thành công'));
				}else{
					return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Số lượng câu hỏi không đủ theo yêu cầu.'));
				}
			}else{
				return \Redirect::back()->withInput()->with('responseData', array('statusCode' => 2, 'message' => 'Chọn file muốn trộn.'));
			}
			
		}
		
		public function troncauhoi($data)				
		{			
			shuffle($data);
			
			return $data;
		}
		
		public function uploadQuestion(Request $request) {
			// print_r($request->all());
			if ( $request->hasFile('docxFile') ) {
				$file = $request->file('docxFile');
				if ($file->getClientOriginalExtension() == 'docx' ) {
					$user=Session::get('user');
					
					$fileName = $user['id'].'_'.time().'.docx';
					$file->move(storage_path('docx'), $fileName);
					
					//Extract data from docx file
					$questionData = $this->getDataFromText($fileName);
					
					$fileId = $request->input('fileId');
					
					if ($questionData && $fileId) {
					
						foreach ($questionData AS $question) {
						
							DB::table('questions')->insert(array('content' => $question['question'], 'folder_id' => $fileId));
							$questionId = DB::getPdo()->lastInsertId();
							
							foreach ($question['answer'] AS $answer) {
							
								DB::table('answers')->insert(array('content' => $answer, 'question_id' => $questionId,'folder_id'=>$fileId));
							
							}
							
							DB::table('answers')->insert(array('content' => $question['correct_answer'], 'question_id' => $questionId, 'correctness' => 1,'folder_id'=>$fileId));
						
						}
						
						return \Redirect::back()->with('responseData', array('statusCode' => 1, 'message' => 'Tải lên dữ liệu thành công'));
					
					} else {
					
						return \Redirect::back()->with('responseData', array('statusCode' => 2, 'message' => 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại nội dung file DOCX'));
					
					}
				
				} else {
				
					return \Redirect::back()->with('responseData', array('statusCode' => 2, 'message' => 'File tải lên phải là DOCX'));
				
				}
			}
		
		}
		
		function extractText($filename) {
			//Check for extension
			$ext = end((explode('.', $filename)));
		 
			//if its docx file
			if($ext == 'docx')
			$dataFile = "word/document.xml";
			//else it must be odt file
			else
			$dataFile = "content.xml";     
			
			// $image = 'images'
			//Create a new ZIP archive object
			$zip = new \ZipArchive;
		 
			// Open the archive file
			if (true === $zip->open(storage_path('docx/'.$filename))) {
				// If successful, search for the data file in the archive
				if (($index = $zip->locateName($dataFile)) !== false) {
					// Index found! Now read it to a string
					$text = $zip->getFromIndex($index);
					// Load XML from a string
					// Ignore errors and warnings
					$DDCM = new \DOMDocument();
					$DDCM->loadXML($text, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
					// echo $xml;exit();
					// Remove XML formatting tags and return the text
					$xmlContent = strip_tags($DDCM->saveXML(), '<w:p><wp:docPr>');
					
					//Add line break tag
					$xmlContent = str_replace('</w:p>', '<br/></w:p>', $xmlContent);
					return strip_tags($xmlContent, '<br>');
					// return $xmlContent;
				}
				//Close the archive file
				$zip->close();
			}
		 
			// In case of failure return a message
			return "File not found";
		}
		
		function getDataFromText($document) {
		
			//Get clean data from docx file
			$cleanData = preg_split('/<br\/>/', trim($this->extractText($document)), -1, PREG_SPLIT_NO_EMPTY);
			
			$questionData = array();
			$questionIndex = $answerIndex = 0;
			$inQuestionLine = $inCorrectLine = false;
			foreach ($cleanData AS $line) {
				if ( strpos($line, '?#') === 0 ) {
				
					$questionIndex++;
					$answerIndex = 0;
					$inQuestionLine = true;
					$questionData[$questionIndex]['question'] = ltrim($line, '?#');
					
				} else if ( strpos($line, '#') === 0 ) {
				
					$answerIndex++;
					$inQuestionLine = $inCorrectLine = false;
					
					if ($questionIndex > 0)
						$questionData[$questionIndex]['answer'][$answerIndex] = ltrim($line, '#');
					
				} else if ( strpos($line, '*#') === 0 ) {
				
					$inQuestionLine = false;
					$inCorrectLine = true;
					
					if ($questionIndex > 0)
						$questionData[$questionIndex]['correct_answer'] = ltrim($line, '*#');
				
				} else if ( $questionIndex > 0 ) {
				
					if ( $inQuestionLine ) {
					
						$questionData[$questionIndex]['question'] .= '\n'.$line;
					
					} else if ( $inCorrectLine ) {
					
						$questionData[$questionIndex]['correct_answer'] .= '\n'.$line;
					
					} else {
					
						$questionData[$questionIndex]['answer'][$answerIndex] .= '\n'.$line;
					
					}
				
				}
			}
			
			if( !empty($questionData) ) {
			
				return $questionData;
			
			}
		
			return false;
			
		}
		
	}