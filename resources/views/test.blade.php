
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
<div align="center">
	<div style="width:970px;text-align:left;font-size:20px">			
		<div class="row">
			<h1 style="text-align:center"><?php echo $data['tests'][0]['name'] ?></h1>
		</div>
		<?php foreach($data['questions'] as $key=>$qs){ ?>
			<div class="row">
				<b>Câu hỏi <?php echo $key+1 ?>:</b> <?php echo $qs['content'] ?>
			</div>
			<?php foreach($data['answers'][$qs['id']] as $ans){ ?>
				<div class="row">
					<?php if($ans['order']==0){ echo "A.";} ?>
					<?php if($ans['order']==1){ echo "B.";} ?>
					<?php if($ans['order']==2){ echo "C.";} ?>
					<?php if($ans['order']==3){ echo "D.";} ?>
					<?php if($ans['order']==4){ echo "E.";} ?>
					<?php echo $ans['content'] ?>
				</div>
			<?php } ?>		
		<?php } ?>
	</div>	
</div>
