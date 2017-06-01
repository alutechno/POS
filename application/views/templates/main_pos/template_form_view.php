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
	<link href=" <?php echo MY_ASSETS ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!-- font Awesome -->
	<link href=" <?php echo MY_ASSETS ?>css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- Ionicons -->
	<link href=" <?php echo MY_ASSETS ?>css/ionicons.min.css" rel="stylesheet" type="text/css"/>
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
<!-- header logo: style can be found in header.less -->
<?php $this->load->view('frame/tpl_header') ?>

<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Left side column. contains the logo and sidebar -->
	<?php $this->load->view('frame/tpl_leftmenu') ?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				<?php echo $title ?><?php echo $button ?>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
				<li><a href="#"> <?php echo ucwords($this->uri->segment('1')) ?></a></li>
				<li class="active"> <?php echo $title ?></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8">
					<!-- general form elements disabled -->
					<div class="box box-warning">
						<div class="box-header">
							<h3 class="box-title">&nbsp;</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<?php $this->load->view($view); ?>
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!--/.col (right) -->
			</div>   <!-- /.row -->
		</section><!-- /.content -->
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<!-- jQuery 2.0.2 -->

<script src=" <?php echo base_url() ?>assets/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src=" <?php echo MY_ASSETS ?>js/bootstrap.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src=" <?php echo MY_ASSETS ?>js/AdminLTE/app.js" type="text/javascript"></script>
</body>
</html>
