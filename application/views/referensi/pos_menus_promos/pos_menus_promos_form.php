<form action="<?php echo $action; ?>" method="post">

	<div class="form-group">
		<label for="int">Outlet Menu Id <?php echo form_error('outlet_menu_id') ?></label>
		<select class="form-control" name="outlet_menu_id" id="outlet_menu_id" required>
			<option value="">- Outlet -</option>
			<?
				$query = $this->db->query("select * from mst_outlet where outlet_type_id=1 or outlet_type_id=2");
				foreach ($query->result() as $row) {
					?>
					<option value="<?= $row->id ?>"><?= $row->name ?></option>
					<?
				}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Name <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name"
			   value="<?php echo $name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   value="<?php echo $description; ?>"/>
	</div>
	<div class="form-group">
		<label for="date">Begin Date <?php echo form_error('begin_date') ?></label>
		<input type="text" class="form-control" name="begin_date" id="begin_date"
			   value="<?php echo $begin_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="date">End Date <?php echo form_error('end_date') ?></label>
		<input type="text" class="form-control" name="end_date" id="end_date"
			   value="<?php echo $end_date; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Begin Time <?php echo form_error('begin_time') ?></label>
		<input type="text" class="form-control" name="begin_time" id="begin_time"
			   value="<?php echo $begin_time; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">End Time <?php echo form_error('end_time') ?></label>
		<input type="text" class="form-control" name="end_time" id="end_time"
			   value="<?php echo $end_time; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Discount Id <?php echo form_error('discount_id') ?></label>
		<select class="form-control" name="discount_id" id="discount_id" required>
			<option value="">- Discount -</option>
			<?
				$query = $this->db->query("select * from mst_pos_discount");
				foreach ($query->result() as $row) {
					?>
					<option value="<?= $row->id ?>"><?= $row->name ?></option>
					<?
				}
			?>
		</select>
	</div>
	<div class="form-group">
		<label for="decimal">Discount Percent <?php echo form_error('discount_percent') ?></label>
		<input type="text" class="form-control" name="discount_percent" id="discount_percent"
			   value="<?php echo $discount_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Discount Amount <?php echo form_error('discount_amount') ?></label>
		<input type="text" class="form-control" name="discount_amount" id="discount_amount"
			   value="<?php echo $discount_amount; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Promo Price <?php echo form_error('promo_price') ?></label>
		<input type="text" class="form-control" name="promo_price" id="promo_price"
			   value="<?php echo $promo_price; ?>"/>
	</div>

	<div class="form-group">
		<label for="varchar">Is Avail Sunday <?php echo form_error('is_avail_sunday') ?></label>
		<!--<input type="text" class="form-control" name="is_avail_sunday" id="is_avail_sunday" placeholder="Is Avail Sunday" value="<?php echo $is_avail_sunday; ?>" />-->
		<input type="checkbox" name="is_avail_sunday" value="1" id="is_avail_sunday">

		<label for="varchar">Is Avail Monday <?php echo form_error('is_avail_monday') ?></label>
		<!-- <input type="text" class="form-control" name="is_avail_monday" id="is_avail_monday" placeholder="Is Avail Monday" value="<?php echo $is_avail_monday; ?>" />-->
		<input type="checkbox" name="is_avail_monday" value="1" id="is_avail_monday">

		<label for="varchar">Is Avail Tuesday <?php echo form_error('is_avail_tuesday') ?></label>
		<!-- <input type="text" class="form-control" name="is_avail_tuesday" id="is_avail_tuesday" placeholder="Is Avail Tuesday" value="<?php echo $is_avail_tuesday; ?>" />-->
		<input type="checkbox" name="is_avail_tuesday" value="1" id="is_avail_tuesday">
	</div>

	<div class="form-group">
		<label for="varchar">Is Avail
			Wednesday <?php echo form_error('is_avail_wednesday') ?></label>
		<input type="checkbox" name="is_avail_wednesday" value="1" id="is_avail_wednesday">
		<label for="varchar">Is Avail Thursday </label>
		<!--<input type="text" class="form-control" name="is_avail_thursday" id="is_avail_thursday" placeholder="Is Avail Thursday" value="<?php echo $is_avail_thursday; ?>" />-->
		<input type="checkbox" name="is_avail_thursday" value="1" id="is_avail_thursday">

		<label for="varchar">Is Avail Friday </label>
		<!-- <input type="text" class="form-control" name="is_avail_friday" id="is_avail_friday" placeholder="Is Avail Friday" value="<?php echo $is_avail_friday; ?>" />-->
		<input type="checkbox" name="is_avail_friday" value="1" id="is_avail_friday">
		<label for="varchar">Is Avail Saturday </label>
		<!--  <input type="text" class="form-control" name="is_avail_saturday" id="is_avail_saturday" placeholder="Is Avail Saturday" value="<?php echo $is_avail_saturday; ?>" />-->
		<input type="checkbox" name="is_avail_saturday" value="1" id="is_avail_saturday">
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('referensi/pos_menus_promos') ?>"
	   class="btn btn-default">Cancel</a>
</form>
