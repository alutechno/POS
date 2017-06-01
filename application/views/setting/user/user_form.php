<!doctype html>
<html>
<head>
	<title>harviacode.com - codeigniter crud generator</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
	<style>
		body {
			padding: 15px;
		}
	</style>
</head>
<body>
<h2 style="margin-top:0px">User <?php echo $button ?></h2>
<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="varchar">Name <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name" placeholder="Name"
			   value="<?php echo $name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Password <?php echo form_error('password') ?></label>
		<input type="text" class="form-control" name="password" id="password" placeholder="Password"
			   value="<?php echo $password; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Token <?php echo form_error('token') ?></label>
		<input type="text" class="form-control" name="token" id="token" placeholder="Token"
			   value="<?php echo $token; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Role Id <?php echo form_error('role_id') ?></label>
		<input type="text" class="form-control" name="role_id" id="role_id" placeholder="Role Id"
			   value="<?php echo $role_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Full Name <?php echo form_error('full_name') ?></label>
		<input type="text" class="form-control" name="full_name" id="full_name"
			   placeholder="Full Name" value="<?php echo $full_name; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Default Module <?php echo form_error('default_module') ?></label>
		<input type="text" class="form-control" name="default_module" id="default_module"
			   placeholder="Default Module" value="<?php echo $default_module; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Default Menu <?php echo form_error('default_menu') ?></label>
		<input type="text" class="form-control" name="default_menu" id="default_menu"
			   placeholder="Default Menu" value="<?php echo $default_menu; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Mobile <?php echo form_error('mobile') ?></label>
		<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile"
			   value="<?php echo $mobile; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Email <?php echo form_error('email') ?></label>
		<input type="text" class="form-control" name="email" id="email" placeholder="Email"
			   value="<?php echo $email; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Image <?php echo form_error('image') ?></label>
		<input type="text" class="form-control" name="image" id="image" placeholder="Image"
			   value="<?php echo $image; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Department Id <?php echo form_error('department_id') ?></label>
		<input type="text" class="form-control" name="department_id" id="department_id"
			   placeholder="Department Id" value="<?php echo $department_id; ?>"/>
	</div>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo site_url('user') ?>" class="btn btn-default">Cancel</a>
</form>
</body>
</html>
