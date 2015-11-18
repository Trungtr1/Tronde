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
			<div class="col-lg-9 col-md-9"  >
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
					<div class="row" style="background-color:#fff;padding:10px;">
						{!! Form::open(array('method' => 'POST','style'=>'margin-bottom:0px;','id'=>'frm_newfolder')) !!}
							<table style="width:100%;">
								<tr>
									<td style="width:160px;"><b>Tạo thư mục mới<b></td>
									<td><input type="text" name="name" class="form-control" value="" placeholder="Tên Thư mục"/></td>
									<td style="width:100px;"><input type="submit" class="btn btn-primary" style="float:right" name="submit" value="Tạo mới" /></td>
								</tr>
							</table>
						{!! Form::close() !!}
					</div>
				<br/>
				<div class="row" style="background-color:#fff;padding:10px;">
					<table style="width:100%;">
						<!--<tr style="height:50px;">
							<th colspan="3" style="border-bottom:1px solid #ccc">
								<button type="button" class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#myModal"></button>&nbsp;&nbsp;Tạo thư mục mới
							</td>
						</th>-->
						<?php foreach($data['folders'] as $fd){ ?>
							<tr class="groups tr2">
								<td>
									<a href="/folder?id=<?php echo $fd['id'] ?>"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo $fd['name'] ?></a>
								</td>
								<td></td>
								<td><span style="float:right;"><?php echo $fd['date'] ?><span></td>
							</tr>
						<?php } ?>
					</table>
				</div>			
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
				{!! Form::open(array('method' => 'PUT','style'=>'margin-bottom:0px;','id'=>'frm_addGroup')) !!}
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
	$(document).on('click','#add_group',function(){
		$('#frm_addGroup').submit();
	})
</script>
@endsection