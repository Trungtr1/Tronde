@extends('templates.master')

@section('title', 'Login')

@section('content')
<style>
	td{
		padding:5px 10px 5px 10px;
	}
	tr{
		height:40px;
	}
	tr:hover{
		background-color:#fff;
	}
</style>
<div class="box">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<h3><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;<?php echo $data['group'][0]['name'] ?></h3>
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
			<div class="container">
				<div class="col-lg-9 col-md-9" style="padding:0px">				
					<div class="row">
						<div class="col-lg-1 col-md-1" style="padding:0px;">
							<button type="button" class="btn btn-primary glyphicon glyphicon-folder-open" data-toggle="modal" data-target="#myModal"></button>
						</div>
						<div class="col-lg-11 col-md-11" style="padding:0px;">
							<p style="font-size:14pt">New Folder</p>
						</div>
					</div>				
				</div>
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
</script>
@endsection