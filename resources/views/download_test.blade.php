<?php
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false); // required for certain browsers
	header("Content-Transfer-Encoding: binary");
	header('Content-Type: application/msword; charset=UTF-8');
	header("Content-Type: application/force-download");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=test.doc");
	header("Content-Transfer-Encoding: binary");
?>
<html>
<style>
	@page section1 {size:595.45pt 841.7pt; margin:1.0in 1.25in 1.0in 1.25in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}
	div.section1 {page:section1;}
	.bold{
		font-size: 14pt;
	}
</style>
<body>
	<div align="center">
		<div class="section1">
			<div style="text-align:left">
				<table class="table" style="width:100%">
					<tr>
						<td>
							<table class="table" style="width:100%">
								<tr>
									<td style="width:40%;vertical-align: top;">
										<b class="bold"><?php echo $data['tests'][0]['school'] ?></b>
									</td>
									<td style="text-align:center">
										<b class="bold"><?php echo $data['tests'][0]['title'] ?></b><br>
										<b class="bold">Môn: <?php echo $data['tests'][0]['subject'] ?></b><br>
										Thời gian: <?php echo $data['tests'][0]['time'] ?> phút<br>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<span style="color:#142CAB">
								Họ tên học sinh:.........................................................
								SBD:..................
								Lớp:............
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<i>
								<b style="color:#142CAB">
									Học sinh giải các bài toán hay trả lời ngắn gọn các câu hỏi vào các dòng trống tương ứng của từng câu (Nhớ ghi rõ đơn vị các đại lượng đã tính).
								</b>
							</i>
						</td>
					</tr>
					<tr>
						<td>
							<table class="table" style="width:100%;">
								<?php for($i=1;$i<=ceil(count($data['questions'])/4);$i++){ ?>
									<tr>
										<td>
											<?php echo 4*($i-1)+1; ?>.&nbsp
											&#9398;&nbsp
											&#9399;&nbsp
											&#9400;&nbsp
											&#9401;
										</td>
										<td>
											<?php echo 4*($i-1)+2; ?>.&nbsp
											&#9398;&nbsp
											&#9399;&nbsp
											&#9400;&nbsp
											&#9401;
										</td>
										<td>
											<?php echo 4*($i-1)+3; ?>.&nbsp
											&#9398;&nbsp
											&#9399;&nbsp
											&#9400;&nbsp
											&#9401;
										</td>
										<td>
											<?php echo 4*($i-1)+4; ?>.&nbsp
											&#9398;&nbsp
											&#9399;&nbsp
											&#9400;&nbsp
											&#9401;
										</td>
									</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div style="margin-top:20px;">
								<b class="bold">Mã đề: <?php echo $data['tests'][0]['code'] ?></b>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="margin-top:10px;">
								<?php foreach($data['questions'] as $key=>$qs){ ?>
									<div class="row" style="margin-top:5px;">
										<b>Câu <?php echo $key+1 ?>:</b> <?php echo $qs['content'] ?>
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
						</td>
					</tr>									
				</table>
			</div>	
		</div>
	</div>
</body>
<html>