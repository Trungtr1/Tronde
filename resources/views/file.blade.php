@extends('templates.master')

@section('title', 'File')

@section('content')
<style>
	td{
		padding:5px 10px 5px 10px;
	}
	tr{
		height:40px;
	}
	.groups:hover{
		//background-color:#DDF0F7;
		background-color:#F5F5F5;
	}
	.icon{
		padding-left: 3px;
		padding-right: 3px;
		padding-top: 2px;
		padding-bottom: 2px;
	}
</style>
<div class="box">
	<div class="container">
		<div class="row">			
			<div class="col-lg-1 col-md-1">
			</div>
			<div class="col-lg-7 col-md-7">
				<h3>					
					<span class="glyphicon glyphicon-list-alt"></span>
					&nbsp;&nbsp;<a href="/user?id=<?php echo $data['user']['id'] ?>"><?php echo $data['user']['fullname'] ?></a>&nbsp;/
					<?php if(!empty($data['parent'])){ ?><a href="/folder?id=<?php echo $data['parent'][0]['id'] ?>"><?php echo $data['parent'][0]['name'] ?></a>&nbsp;/&nbsp;<?php } ?>
					<?php echo $data['file'][0]['name'] ?>
				</h3>
			</div>
			<div class="col-lg-3 col-md-3" style="padding:0px">
				<div class="col-lg-2 col-md-2">
				</div>
				<div class="col-lg-10 col-md-10">
					<table>
						<tr>
							<td>
								{!! Form::open(array('route' => 'upload.to.file', 'method' => 'POST', 'class' => 'inline', 'id' => 'upload-question-form', 'files' => 'true' )) !!}
								<input type="hidden" name="fileId" value="<?php echo $data['file'][0]['id'] ?>">
								<button type="button" style="margin:20px 0px 10px 0px" class="btn btn-default relative"><span class="glyphicon glyphicon-cloud-upload"></span> <?=Form::file('docxFile', array('class' => 'upload-docx-file'));?>Upload</button>
								{!! Form::close() !!}	
							</td>
							<td>
								<button type="button" class="btn btn-danger" style="float:right;margin:20px 0px 10px 0px" data-toggle="modal" data-target="#modal_mixed"><span class="glyphicon glyphicon-refresh"></span> &nbsp;Trộn đề</button>
							</td>
						</tr>						
					</table>				
				</div>						
			</div>
			
		</div>
		<div class="row">
			<div class="col-lg-1 col-md-1">
			</div>
			<div class="col-lg-10 col-md-10">
				<hr style="margin:5px 0px 5px 0px"/>
				@if (Session::has('responseData'))
				@if (Session::get('responseData')['statusCode'] == 1)
					<div class="alert alert-success">{{ Session::get('responseData')['message'] }}</div>
				@elseif (Session::get('responseData')['statusCode'] == 2)
					<div class="alert alert-danger">{{ Session::get('responseData')['message'] }}</div>
				@endif
				@endif

				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-lg-1 col-md-1">
			</div>
			<div class="col-lg-10 col-md-10">
				<?php foreach($data['questions'] as $key=>$qs){ ?>
					<div class="row">
						<b>Câu hỏi <?php echo $key+1 ?>:</b> <?php echo $qs['content'] ?>
					</div>
					<?php foreach($data['answers'][$qs['id']] as $key1=>$ans){ ?>
						<div class="row">
							<?php if($key1==0){ echo "A.";} ?>
							<?php if($key1==1){ echo "B.";} ?>
							<?php if($key1==2){ echo "C.";} ?>
							<?php if($key1==3){ echo "D.";} ?>
							<?php if($key1==4){ echo "E.";} ?>
							<?php echo $ans['content'] ?>
						</div>
					<?php } ?>		
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_mixed" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Thông tin bổ sung</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(array('route' => 'mix.to.folder', 'method' => 'POST', 'class' => 'inline', 'id' => 'mix-question-form', 'files' => 'true' )) !!}
					<div class="row">
						<label>Số lượng đề cần tạo:</label>
					</div>
					<div class="row">
						<input type="text" name="number_tests" class="form-control" value="" />
					</div>
					<br/>
					<div class="row">
						<label>Số lượng câu hỏi mỗi đề:</label>
					</div>
					<div class="row">
						<input type="hidden" name="choosetest[]" value="<?php echo $data['file'][0]['id'] ?>" /> 
						<input type="text" name="number_questions" class="form-control" value="" />
					</div>
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-primary" id="mixed" data-dismiss="modal">Đã Xong</button>
			</div>
		</div>	  
	</div>
</div>
<script type="text/javascript">
	$(document).on('click','#mixed',function(){
		$('#mix-question-form').submit();
	})
	
	$('.upload-docx-file').change(function(){
		
		path = $(this).val().split(/\\/);
		
		if (confirm('Bạn có chắc chắn muốn upload câu hỏi từ file '+(path[path.length-1])+' vào thư mục <?php echo $data['file'][0]['name'] ?>?')) {
		
			$('#upload-question-form').submit();
		
		}
	});
	
	
</script>
@endsection