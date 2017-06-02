
<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="varchar">Code <?php echo form_error('code') ?></label>
		<input type="text" class="form-control" name="code" id="code"
			   value="<?php echo $code; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Name <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name"
			   value="<?php echo $name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Short Name <?php echo form_error('short_name') ?></label>
		<input type="text" class="form-control" name="short_name" id="short_name"
			    value="<?php echo $short_name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   value="<?php echo $description; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<input type="text" class="form-control" name="status" id="status"
			   value="<?php echo $status; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Cover <?php echo form_error('is_cover') ?></label>
		<input type="text" class="form-control" name="is_cover" id="is_cover"
			   value="<?php echo $is_cover; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Captain Order <?php echo form_error('is_captain_order') ?></label>
		<input type="text" class="form-control" name="is_captain_order" id="is_captain_order"
			   value="<?php echo $is_captain_order; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Phone Number <?php echo form_error('is_phone_number') ?></label>
		<input type="text" class="form-control" name="is_phone_number" id="is_phone_number"
			    value="<?php echo $is_phone_number; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Waiter <?php echo form_error('is_waiter') ?></label>
		<input type="text" class="form-control" name="is_waiter" id="is_waiter"
			   value="<?php echo $is_waiter; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Autoincrement Tbl
			No <?php echo form_error('is_autoincrement_tbl_no') ?></label>
		<input type="text" class="form-control" name="is_autoincrement_tbl_no"
			   id="is_autoincrement_tbl_no"
			   value="<?php echo $is_autoincrement_tbl_no; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Guest Profile <?php echo form_error('is_guest_profile') ?></label>
		<input type="text" class="form-control" name="is_guest_profile" id="is_guest_profile"
			    value="<?php echo $is_guest_profile; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Reservation <?php echo form_error('is_reservation') ?></label>
		<input type="text" class="form-control" name="is_reservation" id="is_reservation"
			   value="<?php echo $is_reservation; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Rate Code <?php echo form_error('is_rate_code') ?></label>
		<input type="text" class="form-control" name="is_rate_code" id="is_rate_code"
			   value="<?php echo $is_rate_code; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Service <?php echo form_error('is_service') ?></label>
		<input type="text" class="form-control" name="is_service" id="is_service"
			   value="<?php echo $is_service; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Tax <?php echo form_error('is_tax') ?></label>
		<input type="text" class="form-control" name="is_tax" id="is_tax"
			   value="<?php echo $is_tax; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Surcharge Percent <?php echo form_error('surcharge_percent') ?></label>
		<input type="text" class="form-control" name="surcharge_percent" id="surcharge_percent"
			   value="<?php echo $surcharge_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Min Charge <?php echo form_error('min_charge') ?></label>
		<input type="text" class="form-control" name="min_charge" id="min_charge"
			   value="<?php echo $min_charge; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Hourly Charge <?php echo form_error('hourly_charge') ?></label>
		<input type="text" class="form-control" name="hourly_charge" id="hourly_charge"
			   value="<?php echo $hourly_charge; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Food Entry <?php echo form_error('is_food_entry') ?></label>
		<input type="text" class="form-control" name="is_food_entry" id="is_food_entry"
			   value="<?php echo $is_food_entry; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Beverage
			Alcohol <?php echo form_error('is_beverage_alcohol') ?></label>
		<input type="text" class="form-control" name="is_beverage_alcohol" id="is_beverage_alcohol"
			   value="<?php echo $is_beverage_alcohol; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Beverage Non
			Alcohol <?php echo form_error('is_beverage_non_alcohol') ?></label>
		<input type="text" class="form-control" name="is_beverage_non_alcohol"
			   id="is_beverage_non_alcohol"
			   value="<?php echo $is_beverage_non_alcohol; ?>"/>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('referensi/ref_pos_segment') ?>" class="btn btn-default">Cancel</a>
</form>
</body>
</html>
