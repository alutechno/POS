
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Point Of Sales</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?=MY_ASSETS2?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=MY_ASSETS2?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=MY_ASSETS2?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="<?php echo VIRTUAL_KEYBOARD?>docs/css/jquery-ui.min.css" rel="stylesheet">
	<!-- still using jQuery v2.2.4 because Bootstrap doesn't support v3+ -->
	<script src="<?php echo VIRTUAL_KEYBOARD?>docs/js/jquery-latest.min.js"></script>
	<script src="<?php echo VIRTUAL_KEYBOARD?>docs/js/jquery-ui.min.js"></script>
	<!-- <script src="docs/js/jquery-migrate-3.0.0.min.js"></script> -->
	<!-- keyboard widget css & script (required) -->
	<link href="<?php echo VIRTUAL_KEYBOARD?>css/keyboard.css" rel="stylesheet">
	<script src="<?php echo VIRTUAL_KEYBOARD?>js/jquery.keyboard.js"></script>

	<!-- keyboard extensions (optional) -->
	<script src="<?php echo VIRTUAL_KEYBOARD?>js/jquery.mousewheel.js"></script>
	<script src="<?php echo VIRTUAL_KEYBOARD?>js/jquery.keyboard.extension-typing.js"></script>
	<script src="<?php echo VIRTUAL_KEYBOARD?>js/jquery.keyboard.extension-autocomplete.js"></script>
	<script src="<?php echo VIRTUAL_KEYBOARD?>js/jquery.keyboard.extension-caret.js"></script>
	<!-- demo only -->
	<link href="<?php echo VIRTUAL_KEYBOARD?>docs/css/demo.css" rel="stylesheet">
	<link href="<?php echo VIRTUAL_KEYBOARD?>docs/css/prettify.css" rel="stylesheet">
	<script src="<?php echo VIRTUAL_KEYBOARD?>docs/js/demo.js"></script>
	<script src="<?php echo VIRTUAL_KEYBOARD?>docs/js/jquery.tipsy.min.js"></script>


</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?=base_url()?>"><b>Point Of Sales</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session<br/><?=$alert?></p>

    <form action="<?=base_url('login/proses')?>" method="post">

      <div class="form-group has-feedback">
        <input id="text" type="text" name="userid" class="form-control" placeholder="UserID" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input id="colemak" type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

	   <div class="code ui-corner-all">
			<pre class="prettyprint lang-js">
				$('#text')
					.keyboard({ layout: 'qwerty' })
					.addTyping();</pre>

		<pre class="prettyprint lang-js">
				$('#colemak')
					.keyboard({ layout: 'qwerty' })
					.addTyping();</pre>
		</div>


      <div class="form-group has-feedback">
                                            <select class="form-control" name="outlet" id="outlet" required>
                                                <option value="">- Outlet -</option>
                                        <?php
                                        $query = $this->db->query("select * from mst_outlet where outlet_type_id=1 or outlet_type_id=2");

                                            foreach ($query->result() as $row)
                                            {
                                        ?>
                                                <option value="<?=$row->id?>"><?=$row->name?></option>
                                        <?php
                                        }
                                        ?>
                                            </select>
      </div>

      <div class="row">
        <div class="col-xs-8">

        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?=MY_ASSETS2?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=MY_ASSETS2?>bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->

</body>
</html>
