@extends('templates.master')

@section('title', 'Folder')

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
			<div class="col-lg-9 col-md-9">
				<div class="row" style="padding:0px">
					<div class="panel panel-default">
						<div class="panel-body" style="padding:10px">
							<div class="col-lg-3 col-md-3" style="text-align:center">
								<span class="glyphicon glyphicon-folder-open" style="color:#A9A9A9"></span>&nbsp;&nbsp;thư mục
							</div>
							<div class="col-lg-3 col-md-3" style="text-align:center">
								<span class="glyphicon glyphicon-file" style="color:#A9A9A9"></span>&nbsp;file
							</div>
							<div class="col-lg-3 col-md-3" style="text-align:center">
								<span class="glyphicon glyphicon-star" style="color:#A9A9A9"></span>&nbsp;yêu thích
							</div>
							<div class="col-lg-3 col-md-3" style="text-align:center">
								<span class="glyphicon glyphicon-user" style="color:#A9A9A9"></span>&nbsp;thành viên
							</div>
						</div>
						<div class="panel-heading" style="padding:0px 0px 8px 0px">
							<div class="col-lg-6 col-md-6" style="background-color:#f1e05a;height:8px">
							</div>
							<div class="col-lg-3 col-md-3" style="background-color:#4F5D95;height:8px">
							</div>
							<div class="col-lg-2 col-md-2" style="background-color:#e44b23;height:8px">
							</div>
							<div class="col-lg-1 col-md-1" style="background-color:#563d7c;height:8px">
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="padding:0px">
					<div class="col-lg-2 col-md-2" style="padding:0px">
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Tạo Thư Mục</button>
					</div>
					<div class="col-lg-2 col-md-2" style="padding:0px">
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#addfile"><span class="glyphicon glyphicon-file"></span> &nbsp;Tạo File</button>
					</div>
					<div class="col-lg-8 col-md-8" style="padding:0px">
						<button type="button" class="btn btn-danger" style="float:right;" data-toggle="modal" data-target="#modal_mixed"><span class="glyphicon glyphicon-refresh"></span> &nbsp;Trộn đề</button>
					</div>
				</div>
				<br/>
				<div class="row" style="padding:0px">
					<div class="panel panel-default">
						<div class="panel-heading" style="background-color:#e6f1f6;border:1px solid #c1dce9">
						</div>
						<div class="panel-body" style="padding:0px">
							{!! Form::open(array('route' => 'mix.to.folder', 'method' => 'POST', 'class' => 'inline', 'id' => 'mix-question-form', 'files' => 'true' )) !!}
								<table style="width:100%;padding:0px">
									<?php foreach($data['children'] as $ch){ ?>
										<tr class="groups">
											<td style="border-bottom:1px solid #ccc"><a href="/folder?id=<?php echo $ch['id'] ?>" style="font-size:11pt"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo $ch['name'] ?></a></td>
											<td style="border-bottom:1px solid #ccc;text-align:center;width:150px;">
												<span style="color:#A9A9A9"><?php echo $ch['date'] ?><span>
											</td>
											<td style="border-bottom:1px solid #ccc;text-align:center;width:120px">
												<a href="/folder?id=<?php echo $data['folder'][0]['id'] ?>&action=like&folder_id=<?php echo $ch['id'] ?>">
													<button type="button" title="like" name="like" style="border:0px;background:none;"><span style="color:#159014" class='glyphicon glyphicon-star'></span></button>
												</a>
												<button type="button" title="sửa" name="sua" style="border:0px;background:none;">
													<span style="color:#428bca" class='glyphicon glyphicon-pencil'></span>
												</button>
												<a href="/folder?id=<?php echo $data['folder'][0]['id'] ?>&action=delete&folder_id=<?php echo $ch['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">
													<button type="button" title="xóa" name="xoa" style="border:0px;background:none;"><span style="color:#e44b23" class='glyphicon glyphicon-trash'></span></button>
												</a>
											</td>
										</tr>
									<?php } ?>
									<?php foreach($data['files'] as $fi){ ?>
										<tr class="groups">
											<td style="border-bottom:1px solid #ccc">
												<input type="checkbox"  name="choosetest[]" value="<?php echo $fi['id'] ?>" />&nbsp;&nbsp;
												<a href="/file?id=<?php echo $fi['id'] ?>" style="font-size:11pt;"><span class="glyphicon glyphicon-list-alt" style="color:#000"></span>&nbsp;&nbsp;<?php echo $fi['name'] ?></a>
											</td>
											<td style="border-bottom:1px solid #ccc;text-align:center;width:150px;">
												<span style="color:#A9A9A9"><?php echo $fi['date'] ?><span>
											</td>
											<td style="border-bottom:1px solid #ccc;text-align:center;width:120px">
												<a href="/folder?id=<?php echo $data['folder'][0]['id'] ?>&action=like&folder_id=<?php echo $fi['id'] ?>">
													<button type="button" title="like" name="like" style="border:0px;background:none;"><span style="color:#159014" class='glyphicon glyphicon-star'></span></button>
												</a>
												<button type="button" title="sửa" name="sua" style="border:0px;background:none;">
													<span style="color:#428bca" class='glyphicon glyphicon-pencil'></span>
												</button>
												<a href="/folder?id=<?php echo $data['folder'][0]['id'] ?>&action=delete&folder_id=<?php echo $fi['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">
													<button type="button" title="xóa" name="xoa" style="border:0px;background:none;"><span style="color:#e44b23" class='glyphicon glyphicon-trash'></span></button>
												</a>
											</td>
										</tr>
									<?php } ?>
								</table>
								<div class="modal fade" id="modal_mixed" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title">Thông tin bổ sung</h4>
											</div>
											<div class="modal-body">											
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
														<input type="text" name="number_questions" class="form-control" value="" />
													</div>
												
											</div>
											<div class="modal-footer">
											  <button type="submit" class="btn btn-primary" id="mixed" data-dismiss="modal">Đã Xong</button>
											</div>
										</div>	  
									</div>
								</div>
							{!! Form::close() !!}
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Tạo thư mục</h4>
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
<div class="modal fade" id="addfile" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Tạo file mới</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(array('route'=>'add.file','method' => 'POST','id'=>'frm_newfile')) !!}
					<div class="row">
						<label>Tên file:</label>
					</div>
					<div class="row">
						<input type="text" name="file_name" class="form-control" value="" />
						<input type="hidden" name="folder_id" class="form-control" value="<?php echo $data['folder'][0]['id'] ?>" />
						<input type="hidden" name="folder_level" class="form-control" value="<?php echo $data['folder'][0]['level'] ?>" />
					</div>					
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-primary" id="submit_file" data-dismiss="modal">Submit</button>
			</div>
		</div>
	  
	</div>
</div>
<script type="text/javascript">
	$(document).on('click','#submit',function(){
		$('#frm_newfolder').submit();
	})
	
	$(document).on('click','#mixed',function(){
		$('#mix-question-form').submit();
	})
	
	$(document).on('click','#submit_file',function(){
		$('#frm_newfile').submit();
	})
	
	$('.upload-docx-file').change(function(){
		
		path = $(this).val().split(/\\/);
		
		if (confirm('Bạn có chắc chắn muốn upload câu hỏi từ file '+(path[path.length-1])+' vào thư mục <?php echo $data['folder'][0]['name'] ?>?')) {
		
			$('#upload-question-form').submit();
		
		}
	});
	
	
</script>
@endsection