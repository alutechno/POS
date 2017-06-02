<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	global $template;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo APP_TITLE ?></title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
		  name='viewport'>
	<!-- bootstrap 3.0.2 -->
	<link href="<?php echo MY_ASSETS ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!-- font Awesome -->
	<link href="<?php echo MY_ASSETS ?>css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- Ionicons -->
	<link href="<?php echo MY_ASSETS ?>css/ionicons.min.css" rel="stylesheet" type="text/css"/>
	<!-- Theme style -->
	<link href="<?php echo MY_ASSETS ?>css/AdminLTE.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo MY_ASSETS ?>css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet"
		  type="text/css"/>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>
<body class="skin-black">

<?php $this->load->view('frame/tpl_header') ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<?php $this->load->view('frame/tpl_leftmenu') ?>
	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Report

			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
				<li class="active">Report</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!-- START ALERTS AND CALLOUTS -->

			<?php $this->load->view($view); ?>

		</section><!-- /.content -->
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<!-- jQuery 2.0.2 -->
<!-- Bootstrap -->

<!-- jQuery 2.0.2 -->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo MY_ASSETS ?>js/bootstrap.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo MY_ASSETS ?>js/AdminLTE/app.js" type="text/javascript"></script>
<script src="<?php echo MY_ASSETS ?>js/plugins/daterangepicker/daterangepicker.js"
		type="text/javascript"></script>
<script type="text/javascript">
	$(function () {
		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
	});

	function parsing(number, name) {
		// alert('test');
		var number;
		var name;
		document.getElementById("reportid").value = number;
		document.getElementById("reportname").value = name;
	}

</script>
</body>
</html>
