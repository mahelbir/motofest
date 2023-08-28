<!DOCTYPE html>
<html lang="{{language()}}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@lang('error') - 404</title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" type="text/css">

	<style>
		html, body {
			background-color: #fff;
			color: #636b6f;
			font-family: 'Open Sans', sans-serif;
			font-weight: 100;
			height: 100vh;
			margin: 0;
		}

		.full-height {
			height: 100vh;
		}

		.flex-center {
			align-items: center;
			display: flex;
			justify-content: center;
		}

		.position-ref {
			position: relative;
		}

		.content {
			text-align: center;
		}

		.title {
			font-size: 36px;
			padding: 20px;
		}

		.gradient-button {
			margin: 10px;
			font-family: "Open Sans", Gadget, sans-serif;
			font-size: 20px;
			padding: 15px;
			text-align: center;
			text-transform: uppercase;
			transition: 0.5s;
			background-size: 200% auto;
			color: #FFF;
			box-shadow: 0 0 20px #eee;
			border-radius: 10px;
			width: 200px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
			transition: all 0.3s cubic-bezier(.25,.8,.25,1);
			cursor: pointer;
			display: inline-block;
			border-radius: 25px;
			text-decoration-line: none;
		}

		.gradient-button:hover{
			box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
			margin: 8px 10px 12px;
		}

		.gradient-button-home {
			background-image: linear-gradient(to right, #00d2ff 0%, #3a7bd5 51%, #00d2ff 100%)
		}

		.gradient-button-home:hover {
			background-position: right center;
		}
	</style>

</head>
<body>

<div class="flex-center position-ref full-height">
	<div class="content">
		<div class="title">
			<h1>404</h1>
			@lang('not_found_page')
		</div>
		<a href="@base_url()" class="gradient-button gradient-button-home">@lang('home')</a>
	</div>
</div>

</body>
</html>