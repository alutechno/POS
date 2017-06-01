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
<h2 style="margin-top:0px">Ref_pos_segment Read</h2>
<table class="table">
	<tr>
		<td>Code</td>
		<td><?php echo $code; ?></td>
	</tr>
	<tr>
		<td>Name</td>
		<td><?php echo $name; ?></td>
	</tr>
	<tr>
		<td>Short Name</td>
		<td><?php echo $short_name; ?></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><?php echo $description; ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?php echo $status; ?></td>
	</tr>
	<tr>
		<td>Is Cover</td>
		<td><?php echo $is_cover; ?></td>
	</tr>
	<tr>
		<td>Is Captain Order</td>
		<td><?php echo $is_captain_order; ?></td>
	</tr>
	<tr>
		<td>Is Phone Number</td>
		<td><?php echo $is_phone_number; ?></td>
	</tr>
	<tr>
		<td>Is Waiter</td>
		<td><?php echo $is_waiter; ?></td>
	</tr>
	<tr>
		<td>Is Autoincrement Tbl No</td>
		<td><?php echo $is_autoincrement_tbl_no; ?></td>
	</tr>
	<tr>
		<td>Is Guest Profile</td>
		<td><?php echo $is_guest_profile; ?></td>
	</tr>
	<tr>
		<td>Is Reservation</td>
		<td><?php echo $is_reservation; ?></td>
	</tr>
	<tr>
		<td>Is Rate Code</td>
		<td><?php echo $is_rate_code; ?></td>
	</tr>
	<tr>
		<td>Is Service</td>
		<td><?php echo $is_service; ?></td>
	</tr>
	<tr>
		<td>Is Tax</td>
		<td><?php echo $is_tax; ?></td>
	</tr>
	<tr>
		<td>Surcharge Percent</td>
		<td><?php echo $surcharge_percent; ?></td>
	</tr>
	<tr>
		<td>Min Charge</td>
		<td><?php echo $min_charge; ?></td>
	</tr>
	<tr>
		<td>Hourly Charge</td>
		<td><?php echo $hourly_charge; ?></td>
	</tr>
	<tr>
		<td>Is Food Entry</td>
		<td><?php echo $is_food_entry; ?></td>
	</tr>
	<tr>
		<td>Is Beverage Alcohol</td>
		<td><?php echo $is_beverage_alcohol; ?></td>
	</tr>
	<tr>
		<td>Is Beverage Non Alcohol</td>
		<td><?php echo $is_beverage_non_alcohol; ?></td>
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
		<td><a href="<?php echo site_url('ref_pos_segment') ?>" class="btn btn-default">Cancel</a>
		</td>
	</tr>
</table>
</body>
</html>
