@extends('templates.master')

@section('title', 'User')

@section('content')
<style>
	td{
		padding:5px 10px 5px 10px;
	}
	th{
		padding:5px 10px 5px 10px;
	}
	tr{
		height:30px;
	}
	.tr2{
		height:40px;
	}
	.groups:hover{
		background-color:#EFEFEF;
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
		</br>
		<div class="row">
			<div class="col-lg-3 col-md-3">
				<div class="row" style="padding:0px">
					<div class="col-lg-12 col-md-12" style="padding:0px;">
						<table style="width:100%">
							<tr>
								<td style="color:#A9A9A9"><b style="font-size:11pt">NHÓM</b></td>
							</tr>
							<?php foreach($data['groups'] as $gr){ ?>
								<tr class="groups">
									<td><a href="/group?id=<?php echo $gr['id'] ?>" style="color:#333;font-size:11pt"><span class="glyphicon glyphicon-share-alt icon"></span>&nbsp;<?php echo $gr['name'] ?></a></td>
								</tr>
							<?php } ?>
							<tr class="groups">
								<td>
									<button type="button" class="btn btn-warning glyphicon glyphicon-plus icon" data-toggle="modal" data-target="#addGroup"></button>&nbsp;<span style="color:#333;font-size:11pt">Tạo Nhóm</span>
								</td>
							</tr>							
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6"  >
				<!--<div class="row" style="background-color:#fff;padding:10px;">
					<table style="width:100%">
						<tr>
							<td rowspan="2" style="width:120px;"><img style="width:100px;" src="{{ asset('public/img/avata_default.jpg') }}" /></td>
							<td><h2 style="margin:0px;"><?php echo $data['user']['fullname'] ?></h2></td>
						</tr>
						<tr>
							<td>
								<span style="color:#ccc" class="glyphicon glyphicon-envelope"></span>&nbsp;<?php echo $data['user']['email'] ?>&nbsp;&nbsp;
								<span style="color:#ccc" class="glyphicon glyphicon-phone"></span>&nbsp;<?php echo $data['user']['phone'] ?>&nbsp;&nbsp;
								<span style="color:#ccc" class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $data['user']['address'] ?>
							</td>
						</tr>
					</table>					
				</div>
				</br>-->
					<div class="row" style="background-color:#fff;padding:10px 0px 10px 0px;">
						{!! Form::open(array('method' => 'POST','style'=>'margin-bottom:0px;','id'=>'frm_newfolder')) !!}
							<table style="width:100%;">
								<tr>
									<td style="width:160px;"><b>Tạo thư mục mới<b></td>
									<td><input type="text" name="folder_name" class="form-control" value="" placeholder="Tên Thư mục"/></td>
									<td style="width:100px;"><input type="submit" class="btn btn-primary" style="float:right" name="submit" value="Tạo mới" /></td>
								</tr>
							</table>
						{!! Form::close() !!}
					</div>
				<br/>
				<div class="row" style="background-color:#fff;">
					{!! Form::open(array('method' => 'PUT','style'=>'margin-bottom:0px;','id'=>'frm_mixed')) !!}
					<table style="width:100%;">
						<!--<tr style="height:50px;">
							<th colspan="3" style="border-bottom:1px solid #ccc">
								<button type="button" class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#myModal"></button>&nbsp;&nbsp;Tạo thư mục mới
							</td>
						</th>-->
						<?php foreach($data['folders'] as $fd){ ?>
							<tr class="groups tr2">
								<td style="border-bottom:1px solid #ccc">
									<a href="/folder?id=<?php echo $fd['id'] ?>"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo $fd['name'] ?></a>
								</td>								
								<td style="border-bottom:1px solid #ccc;text-align:center;width:150px;">
									<span style="color:#A9A9A9"><?php echo $fd['date'] ?><span>
								</td>
								<td style="border-bottom:1px solid #ccc;text-align:center;width:40px;"><?php if($fd['like']==1){ ?>
									<span style="color:#159014" class="glyphicon glyphicon-star"></span><?php } ?>
								</td>
								<td style="border-bottom:1px solid #ccc;text-align:center;width:40px;">
									<a href="/user?action=delete&id=<?php echo $fd['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');"><button type="button" title="xóa" name="xoa" style="border:0px;background:none;"><span style="color:#A9A9A9" class='glyphicon glyphicon-trash'></span></button></a>
								</td>
								<td style="border-bottom:1px solid #ccc;width:40px;">
									<input type="checkbox" style="height:20px;width:20px;float:right;" name="choosetest[]" value="<?php echo $fd['id'] ?>" />
								</td>
							</tr>													
						<?php } ?>
						<tr style="height:50px">
							<td colspan="5" style="text-align:right">
								<!--<input type="submit" class="btn btn-primary" name="mixed" value="Trộn đề" />-->
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_mixed">Trộn đề</button>
							</td>
						</tr>
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
			<div class="col-lg-3 col-md-3" style="height:400px;background-color:#fff">
				<div class="row">
					<h3>Danh sách đề được tạo</h3>
				</div>
				<hr style="margin:5px 0px 5px 0px"/>
				<?php foreach($data['tests'] as $value){ ?>
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<a href="/test?id=<?php echo $value['id'] ?>" target="_blank"><?php echo $value['name'] ?></a>
						</div>
						<div class="col-lg-6 col-md-6">
							<a href="/answer?id=<?php echo $value['id'] ?>" style="float:right" target="_blank">đáp án</a>
						</div>						
					</div>
					<hr style="margin:5px 0px 5px 0px"/>
				<?php } ?>
			</div>
		</div>
	</div>	
</div>
<div class="modal fade" id="addGroup" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Tạo Nhóm Mới</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(array('method' => 'POST','style'=>'margin-bottom:0px;','id'=>'frm_addGroup')) !!}
					<div class="row">
						<label>Tên Nhóm:</label>
					</div>
					<div class="row">
						<input type="text" name="group_name" class="form-control" value="" />
					</div>					
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-primary" id="add_group" data-dismiss="modal">Tạo mới</button>
			</div>
		</div>	  
	</div>
</div>
<script type="text/javascript">
	$(document).on('click','#mixed',function(){
		$('#frm_mixed').submit();
	})
	$(document).on('click','#add_group',function(){
		$('#frm_addGroup').submit();
	})
</script>
@endsection