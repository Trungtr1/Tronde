<html>
    <head>
        <title>@yield('title')</title>
		<link rel="stylesheet" href="{{ asset('public/css/bootstrap.css') }}">
		<link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
		<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
		
		<script src="{{ asset('public/js/jquery.min.js') }}"></script>
		<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('public/js/app.js?v=1.1') }}"></script>
    </head>
    <!--<body style="background-color:#F5F5F5;">-->
	<body>
		<style>
			#header{
				background-color:#428bca;
				height:55px;
				line-height:55px;
			}
			.pd0{
				padding:0px;
			}
		</style>
		<div id="header" >
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 top-header">
						<div id="logo" style="" class="col-lg-2 col-md-2 pd0">
							<b style="font-size:32px;height:26px;color:#fff">Mixed</b>
						</div>
						<div class="col-lg-10 col-md-10">								
							<div class="header-title" style="font-size: 18px;padding:0;">
								
							</div>	
						</div>
					</div>
				</div>				
			</div>	
		</div>
		<div id="main-content">			
			<div class="row">
				<div class="col-lg-12 col-md-12 pd0">
					@yield('content')
				</div>
			</div>
		</div>
		<div id="footer" style="height:40px;">
		</div>
	</body>
</html>