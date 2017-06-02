<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	global $template;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> <?php echo APP_TITLE ?></title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
		  name='viewport'>
	<!-- bootstrap 3.0.2 -->
	<link href="<?php echo MY_ASSETS ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!-- font Awesome -->
	<link href=" <?php echo MY_ASSETS ?>css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- Ionicons -->
	<link href=" <?php echo MY_ASSETS ?>css/ionicons.min.css" rel="stylesheet" type="text/css"/>
	<!-- Morris chart -->
	<link href=" <?php echo MY_ASSETS ?>css/morris/morris.css" rel="stylesheet" type="text/css"/>
	<!-- jvectormap -->
	<link href=" <?php echo MY_ASSETS ?>css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet"
		  type="text/css"/>
	<!-- fullCalendar -->
	<link href=" <?php echo MY_ASSETS ?>css/fullcalendar/fullcalendar.css" rel="stylesheet"
		  type="text/css"/>
	<!-- Daterange picker -->
	<link href=" <?php echo MY_ASSETS ?>css/daterangepicker/daterangepicker-bs3.css"
		  rel="stylesheet" type="text/css"/>
	<!-- bootstrap wysihtml5 - text editor -->
	<link href=" <?php echo MY_ASSETS ?>css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"
		  rel="stylesheet" type="text/css"/>
	<!-- Theme style -->
	<link href=" <?php echo MY_ASSETS ?>css/AdminLTE.css" rel="stylesheet" type="text/css"/>

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
		<?php $this->load->view($view); ?>
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<!-- add new calendar event modal -->
<script src=" <?php echo base_url() ?>assets/js/jquery.min.js"></script>
<!-- jQuery 2.0.2 -->
<!-- jQuery UI 1.10.3 -->
<script src=" <?php echo MY_ASSETS ?>js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
<!-- Bootstrap -->
<script src=" <?php echo MY_ASSETS ?>js/bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src=" <?php echo MY_ASSETS ?>js/plugins/morris/morris.min.js"
		type="text/javascript"></script>
<!-- Sparkline -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/sparkline/jquery.sparkline.min.js"
		type="text/javascript"></script>
<!-- jvectormap -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"
		type="text/javascript"></script>
<script src=" <?php echo MY_ASSETS ?>js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"
		type="text/javascript"></script>
<!-- fullCalendar -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/fullcalendar/fullcalendar.min.js"
		type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/jqueryKnob/jquery.knob.js"
		type="text/javascript"></script>
<!-- daterangepicker -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/daterangepicker/daterangepicker.js"
		type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"
		type="text/javascript"></script>
<!-- iCheck -->
<script src=" <?php echo MY_ASSETS ?>js/plugins/iCheck/icheck.min.js"
		type="text/javascript"></script>

<!-- AdminLTE App -->
<script src=" <?php echo MY_ASSETS ?>js/AdminLTE/app.js" type="text/javascript"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src=" <?php echo MY_ASSETS ?>js/AdminLTE/dashboard.js" type="text/javascript"></script>

</body>
</html>
