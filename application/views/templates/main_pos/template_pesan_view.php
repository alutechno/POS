<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
global $template;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= APP_TITLE ?></title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
		  name='viewport'>
	<!-- bootstrap 3.0.2 -->
	<link href="<?= MY_ASSETS ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!-- font Awesome -->
	<link href="<?= MY_ASSETS ?>css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- Ionicons -->
	<link href="<?= MY_ASSETS ?>css/ionicons.min.css" rel="stylesheet" type="text/css"/>
	<!-- Theme style -->
	<link href="<?= MY_ASSETS ?>css/AdminLTE.css" rel="stylesheet" type="text/css"/>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

</head>
<body class="skin-black">

<?php $this->load->view($view); ?>

<!-- jQuery 2.0.2 -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= MY_ASSETS ?>js/bootstrap.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->

</body>
</html>
