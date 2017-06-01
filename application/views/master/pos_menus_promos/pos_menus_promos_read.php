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
<h2 style="margin-top:0px">Pos_menus_promos Read</h2>
<table class="table">
	<tr>
		<td>Outlet Menu Id</td>
		<td><?php echo $outlet_menu_id; ?></td>
	</tr>
	<tr>
		<td>Name</td>
		<td><?php echo $name; ?></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><?php echo $description; ?></td>
	</tr>
	<tr>
		<td>Begin Date</td>
		<td><?php echo $begin_date; ?></td>
	</tr>
	<tr>
		<td>End Date</td>
		<td><?php echo $end_date; ?></td>
	</tr>
	<tr>
		<td>Begin Time</td>
		<td><?php echo $begin_time; ?></td>
	</tr>
	<tr>
		<td>End Time</td>
		<td><?php echo $end_time; ?></td>
	</tr>
	<tr>
		<td>Discount Id</td>
		<td><?php echo $discount_id; ?></td>
	</tr>
	<tr>
		<td>Discount Percent</td>
		<td><?php echo $discount_percent; ?></td>
	</tr>
	<tr>
		<td>Discount Amount</td>
		<td><?php echo $discount_amount; ?></td>
	</tr>
	<tr>
		<td>Promo Price</td>
		<td><?php echo $promo_price; ?></td>
	</tr>
	<tr>
		<td>Is Avail Sunday</td>
		<td><?php echo $is_avail_sunday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Monday</td>
		<td><?php echo $is_avail_monday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Tuesday</td>
		<td><?php echo $is_avail_tuesday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Wednesday</td>
		<td><?php echo $is_avail_wednesday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Thursday</td>
		<td><?php echo $is_avail_thursday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Friday</td>
		<td><?php echo $is_avail_friday; ?></td>
	</tr>
	<tr>
		<td>Is Avail Saturday</td>
		<td><?php echo $is_avail_saturday; ?></td>
	</tr>
	<tr>
		<td>Created Date</td>
		<td><?php echo $created_date; ?></td>
	</tr>
	<tr>
		<td>Modified Date</td>
		<td><?php echo $modified_date; ?></td>
	</tr>
	<tr>
		<td>Created By</td>
		<td><?php echo $created_by; ?></td>
	</tr>
	<tr>
		<td>Modified By</td>
		<td><?php echo $modified_by; ?></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo site_url('pos_menus_promos') ?>" class="btn btn-default">Cancel</a>
		</td>
	</tr>
</table>
</body>
</html>
