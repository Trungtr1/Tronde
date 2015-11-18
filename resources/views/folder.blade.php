@extends('templates.master')

@section('title', 'Folder')

@section('content')
<style>
	td{
		padding:5px 10px 5px 10px;
	}
	tr{
		height:40px;
		background-color:#fff;
	}
	tr:hover{
		background-color:#F5F5F5;
	}
</style>
<div class="box">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<h3>					
					<span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;<a href="/user?id=<?php echo $data['user']['id'] ?>"><?php echo $data['user']['fullname'] ?></a>&nbsp;/
					<?php if(isset($data['parent'])){ ?>
						<?php foreach($data['parent'] as $pr){ ?>
							<a href="/folder?id=<?php echo $pr['id'] ?>"><?php echo $pr['name'] ?></a>&nbsp;/
						<?php } ?>
					<?php } ?>
					<?php echo $data['folder'][0]['name'] ?>
				</h3>
				<hr>
			</div>
		</div>
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
		<div class="row">			
			<div class="col-lg-12 col-md-12">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;New Folder</button>
				{!! Form::open(array('route' => 'upload.to.folder', 'method' => 'POST', 'class' => 'inline', 'id' => 'upload-question-form', 'files' => 'true' )) !!}
				<input type="hidden" name="folderId" value="<?php echo $data['folder'][0]['id'] ?>">
				<button type="button" class="btn btn-default relative"><span class="glyphicon glyphicon-cloud-upload"></span> <?=Form::file('docxFile', array('class' => 'upload-docx-file'));?>Upload</button>
				{!! Form::close() !!}
			</div>		
		</div>
		</br>
		<?php if(!empty($data['children'])){ ?>
		<div class="row">
			<div class="col-lg-9 col-md-9">
				<table style="width:100%;border:1px solid #ccc;border-radius: 4px;">
					<tr style="background-color:#e6f1f6;">
						<th style="border-bottom:1px solid #ccc;" colspan="3"></th>
					</tr>
					<?php foreach($data['children'] as $ch){ ?>
						<tr>
							<td style="border-bottom:1px solid #ccc;">
								<a href="/folder?id=<?php echo $ch['id'] ?>"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo $ch['name'] ?></a>
							</td>
							<td style="border-bottom:1px solid #ccc;"></td>
							<td style="border-bottom:1px solid #ccc;"><span style="float:right;"><?php echo $ch['date'] ?><span></td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Modal Header</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(array('method' => 'POST','style'=>'margin-bottom:0px;','id'=>'frm_newfolder')) !!}
					<div class="row">
						<label>Folder name:</label>
					</div>
					<div class="row">
						<input type="text" name="name" class="form-control" value="" />
						<input type="hidden" name="id" class="form-control" value="<?php echo $data['folder'][0]['id'] ?>" />
						<input type="hidden" name="level" class="form-control" value="<?php echo $data['folder'][0]['level'] ?>" />
					</div>					
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-primary" id="submit" data-dismiss="modal">Submit</button>
			</div>
		</div>
	  
	</div>
</div>
<script type="text/javascript">
	$(document).on('click','#submit',function(){
		$('#frm_newfolder').submit();
	})
	
	$('.upload-docx-file').change(function(){
		
		path = $(this).val().split(/\\/);
		
		if (confirm('Bạn có chắc chắn muốn upload câu hỏi từ file '+(path[path.length-1])+' vào thư mục <?php echo $data['folder'][0]['name'] ?>?')) {
		
			$('#upload-question-form').submit();
		
		}
	});
	
	
</script>
@endsection