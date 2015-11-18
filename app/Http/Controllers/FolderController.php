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
					
					$folderId = $request->input('folderId');
					
					if ($questionData && $folderId) {
					
						foreach ($questionData AS $question) {
						
							DB::table('questions')->insert(array('content' => $question['question'], 'folder_id' => $folderId));
							$questionId = DB::getPdo()->lastInsertId();
							
							foreach ($question['answer'] AS $answer) {
							
								DB::table('answers')->insert(array('content' => $answer, 'question_id' => $questionId));
							
							}
							
							DB::table('answers')->insert(array('content' => $question['correct_answer'], 'question_id' => $questionId, 'correctness' => 1));
						
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