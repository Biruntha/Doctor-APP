<!doctype html>
 <html xmlns:th="http://www.thymeleaf.org">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Account Activation Email Template</title>
    <meta name="description" content="Account Activation Email Template.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style type="text/css">
		body{
		font-family: poppins;
		font-size:14px;
		}
        a:hover, a {text-decoration: none !important;}
		
		.container{
			background:#fff;
			border-radius:20px;
			width:95%;
			max-width:600px;
			margin:0 auto;
			position:relative;
		}
		
		.bg-grad{
			background:linear-gradient(180deg, rgba(28, 146, 210, 0.9) 0%, rgba(0, 206, 212, 0.9) 100%);
		}
		
		.divider{
			height:8px;width:100%;
		}
		.text-theme{
			color:rgb(28, 146, 210);
			font-weight:bold;
		}
		.btn-theme{
			background:rgb(28, 146, 210);
			color:#fff !important;
			font-weight:bold;
			padding:10px 30px;
			border-radius:10px;
			margin-top:20px;
			
		}
		
		.heading{
			width:100%;
			text-align:center;
			font-weight:bold;
			font-size:15px;
			padding-top:15px;
		}
		
		.text-left{
			text-align:left;
		}
		
		.bold{
			font-weight:900 !important;
		}
		
		.table{
			width:100%;
		}
		
		.table td{
			padding:7px 5px 7px 20px;
			border-top:1px solid #ddd;
		}
		
    </style>


<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #fff;text-align:center" leftmargin="0" >
	<img src="https://app.allthebestlinks.com/assets/images/logo.png" style="width:220px;margin:0 auto; margin-bottom:10px;margin-top:30px" />
		
	<div class="container">
		<h1 class="heading">Confirm your email.</h1>
		<p class="text-left">
			Dear <span class="text-theme">{{ $details['name'] }}</span>,<br/>
			You have successfully registered your account, before being able to use your account you need to verify that this is your email address by clicking here. 
		</p>
	</div>
	
	<div class="container">
		<a class="btn-theme" href="{{ url($details['token']) }}">Verify Email</a>
	</div>
</body>

</html>