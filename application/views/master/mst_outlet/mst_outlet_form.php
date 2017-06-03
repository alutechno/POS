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
		<label for="varchar">Address <?php echo form_error('address') ?></label>
		<input type="text" class="form-control" name="address" id="address"
			   value="<?php echo $address; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Bill Footer <?php echo form_error('bill_footer') ?></label>
		<input type="text" class="form-control" name="bill_footer" id="bill_footer"
			   value="<?php echo $bill_footer; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<input type="text" class="form-control" name="status" id="status"
			   value="<?php echo $status; ?>"/>
	</div>

	<div class="form-group">
		<label for="int">Outlet Type Id <?php echo form_error('outlet_type_id') ?></label>
		<select class="form-control" name="outlet_type_id" id="outlet_type_id">
			<?php
			$query = $this->db->query("select * from ref_outlet_type where status=1 ");

			foreach ($query->result() as $row) {
				?>
				<option value='<?= $row->id ?>'><?= $row->name ?></option>
				<?php
			}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="int">Cost Center Id <?php echo form_error('cost_center_id') ?></label>
		<select class="form-control" name="cost_center_id" id="cost_center_id">
			<?
			$query = $this->db->query("select * from mst_cost_center where `status`=1 ");

			foreach ($query->result() as $row) {
				?>
				<option value='<?= $row->id ?>'><?= $row->name ?></option>
				<?
			}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Delivery Bill
			Footer <?php echo form_error('delivery_bill_footer') ?></label>
		<input type="text" class="form-control" name="delivery_bill_footer"
			   id="delivery_bill_footer"
			   value="<?php echo $delivery_bill_footer; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">No Of Seats <?php echo form_error('no_of_seats') ?></label>
		<input type="text" class="form-control" name="no_of_seats" id="no_of_seats"
			   value="<?php echo $no_of_seats; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">M2 <?php echo form_error('m2') ?></label>
		<input type="text" class="form-control" name="m2" id="m2"
			   value="<?php echo $m2; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Last Meal Period <?php echo form_error('last_meal_period') ?></label>
		<input type="text" class="form-control" name="last_meal_period" id="last_meal_period"
			   value="<?php echo $last_meal_period; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Curr Meal Period <?php echo form_error('curr_meal_period') ?></label>
		<input type="text" class="form-control" name="curr_meal_period" id="curr_meal_period"
			   value="<?php echo $curr_meal_period; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">List Number <?php echo form_error('list_number') ?></label>
		<input type="text" class="form-control" name="list_number" id="list_number"
			   value="<?php echo $list_number; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Num Of Employee <?php echo form_error('num_of_employee') ?></label>
		<input type="text" class="form-control" name="num_of_employee" id="num_of_employee"
			   value="<?php echo $num_of_employee; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Allow Cancel
			Tax <?php echo form_error('is_allow_cancel_tax') ?></label>
		<input type="text" class="form-control" name="is_allow_cancel_tax" id="is_allow_cancel_tax"
			   value="<?php echo $is_allow_cancel_tax; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Fo Gl Journal Code <?php echo form_error('fo_gl_journal_code') ?></label>
		<input type="text" class="form-control" name="fo_gl_journal_code" id="fo_gl_journal_code"
			   value="<?php echo $fo_gl_journal_code; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Bill Image Uri <?php echo form_error('bill_image_uri') ?></label>
		<input type="text" class="form-control" name="bill_image_uri" id="bill_image_uri"
			   value="<?php echo $bill_image_uri; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Bill Image Path <?php echo form_error('bill_image_path') ?></label>
		<input type="text" class="form-control" name="bill_image_path" id="bill_image_path"
			   value="<?php echo $bill_image_path; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Small Bill Image
			Uri <?php echo form_error('small_bill_image_uri') ?></label>
		<input type="text" class="form-control" name="small_bill_image_uri"
			   id="small_bill_image_uri"
			   value="<?php echo $small_bill_image_uri; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Small Bill Image
			Path <?php echo form_error('small_bill_image_path') ?></label>
		<input type="text" class="form-control" name="small_bill_image_path"
			   id="small_bill_image_path"
			   value="<?php echo $small_bill_image_path; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Printed Bill Image
			Uri <?php echo form_error('printed_bill_image_uri') ?></label>
		<input type="text" class="form-control" name="printed_bill_image_uri"
			   id="printed_bill_image_uri"
			   value="<?php echo $printed_bill_image_uri; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Printed Bill Image
			Path <?php echo form_error('printed_bill_image_path') ?></label>
		<input type="text" class="form-control" name="printed_bill_image_path"
			   id="printed_bill_image_path"
			   value="<?php echo $printed_bill_image_path; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Small Printed Bill Image
			Uri <?php echo form_error('small_printed_bill_image_uri') ?></label>
		<input type="text" class="form-control" name="small_printed_bill_image_uri"
			   id="small_printed_bill_image_uri"
			   value="<?php echo $small_printed_bill_image_uri; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Small Printed Bill Image
			Path <?php echo form_error('small_printed_bill_image_path') ?></label>
		<input type="text" class="form-control" name="small_printed_bill_image_path"
			   id="small_printed_bill_image_path"
			   value="<?php echo $small_printed_bill_image_path; ?>"/>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('master/mst_outlet') ?>" class="btn btn-default">Cancel</a>
</form>
