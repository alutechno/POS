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
<h2 style="margin-top:0px">Pos_menus_promos <?php echo $button ?></h2>
<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="int">Outlet Menu Id <?php echo form_error('outlet_menu_id') ?></label>
		<input type="text" class="form-control" name="outlet_menu_id" id="outlet_menu_id"
			   placeholder="Outlet Menu Id" value="<?php echo $outlet_menu_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Name <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name" placeholder="Name"
			   value="<?php echo $name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   placeholder="Description" value="<?php echo $description; ?>"/>
	</div>
	<div class="form-group">
		<label for="date">Begin Date <?php echo form_error('begin_date') ?></label>
		<input type="text" class="form-control" name="begin_date" id="begin_date"
			   placeholder="Begin Date" value="<?php echo $begin_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="date">End Date <?php echo form_error('end_date') ?></label>
		<input type="text" class="form-control" name="end_date" id="end_date" placeholder="End Date"
			   value="<?php echo $end_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Begin Time <?php echo form_error('begin_time') ?></label>
		<input type="text" class="form-control" name="begin_time" id="begin_time"
			   placeholder="Begin Time" value="<?php echo $begin_time; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">End Time <?php echo form_error('end_time') ?></label>
		<input type="text" class="form-control" name="end_time" id="end_time" placeholder="End Time"
			   value="<?php echo $end_time; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Discount Id <?php echo form_error('discount_id') ?></label>
		<input type="text" class="form-control" name="discount_id" id="discount_id"
			   placeholder="Discount Id" value="<?php echo $discount_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Discount Percent <?php echo form_error('discount_percent') ?></label>
		<input type="text" class="form-control" name="discount_percent" id="discount_percent"
			   placeholder="Discount Percent" value="<?php echo $discount_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Discount Amount <?php echo form_error('discount_amount') ?></label>
		<input type="text" class="form-control" name="discount_amount" id="discount_amount"
			   placeholder="Discount Amount" value="<?php echo $discount_amount; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Promo Price <?php echo form_error('promo_price') ?></label>
		<input type="text" class="form-control" name="promo_price" id="promo_price"
			   placeholder="Promo Price" value="<?php echo $promo_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Sunday <?php echo form_error('is_avail_sunday') ?></label>
		<input type="text" class="form-control" name="is_avail_sunday" id="is_avail_sunday"
			   placeholder="Is Avail Sunday" value="<?php echo $is_avail_sunday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Monday <?php echo form_error('is_avail_monday') ?></label>
		<input type="text" class="form-control" name="is_avail_monday" id="is_avail_monday"
			   placeholder="Is Avail Monday" value="<?php echo $is_avail_monday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Tuesday <?php echo form_error('is_avail_tuesday') ?></label>
		<input type="text" class="form-control" name="is_avail_tuesday" id="is_avail_tuesday"
			   placeholder="Is Avail Tuesday" value="<?php echo $is_avail_tuesday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail
			Wednesday <?php echo form_error('is_avail_wednesday') ?></label>
		<input type="text" class="form-control" name="is_avail_wednesday" id="is_avail_wednesday"
			   placeholder="Is Avail Wednesday" value="<?php echo $is_avail_wednesday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Thursday <?php echo form_error('is_avail_thursday') ?></label>
		<input type="text" class="form-control" name="is_avail_thursday" id="is_avail_thursday"
			   placeholder="Is Avail Thursday" value="<?php echo $is_avail_thursday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Friday <?php echo form_error('is_avail_friday') ?></label>
		<input type="text" class="form-control" name="is_avail_friday" id="is_avail_friday"
			   placeholder="Is Avail Friday" value="<?php echo $is_avail_friday; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Avail Saturday <?php echo form_error('is_avail_saturday') ?></label>
		<input type="text" class="form-control" name="is_avail_saturday" id="is_avail_saturday"
			   placeholder="Is Avail Saturday" value="<?php echo $is_avail_saturday; ?>"/>
	</div>
	<div class="form-group">
		<label for="timestamp">Created Date <?php echo form_error('created_date') ?></label>
		<input type="text" class="form-control" name="created_date" id="created_date"
			   placeholder="Created Date" value="<?php echo $created_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="timestamp">Modified Date <?php echo form_error('modified_date') ?></label>
		<input type="text" class="form-control" name="modified_date" id="modified_date"
			   placeholder="Modified Date" value="<?php echo $modified_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Created By <?php echo form_error('created_by') ?></label>
		<input type="text" class="form-control" name="created_by" id="created_by"
			   placeholder="Created By" value="<?php echo $created_by; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Modified By <?php echo form_error('modified_by') ?></label>
		<input type="text" class="form-control" name="modified_by" id="modified_by"
			   placeholder="Modified By" value="<?php echo $modified_by; ?>"/>
	</div>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo site_url('pos_menus_promos') ?>" class="btn btn-default">Cancel</a>
</form>
</body>
</html>
