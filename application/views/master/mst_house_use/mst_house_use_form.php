<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="varchar">Code <?php echo form_error('code') ?></label>
		<input type="text" class="form-control" name="code" id="code" placeholder="Code"
			   value="<?php echo $code; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Name <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name" placeholder="Name"
			   value="<?php echo $name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Short Name <?php echo form_error('short_name') ?></label>
		<input type="text" class="form-control" name="short_name" id="short_name"
			   placeholder="Short Name" value="<?php echo $short_name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   placeholder="Description" value="<?php echo $description; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<input type="text" class="form-control" name="status" id="status" placeholder="Status"
			   value="<?php echo $status; ?>"/>
	</div>

	<div class="form-group">
		<label for="int">Pos Cost Center Id <?php echo form_error('pos_cost_center_id') ?></label>
		<select class="form-control" name="pos_cost_center_id" id="pos_cost_center_id">
			<?php
				$query = $this->db->query("select * from mst_pos_cost_center");
				foreach ($query->result() as $row) {
					?>
					<option value='<?php $row->id ?>'><?= $row->name ?></option>
					<?php
				}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="decimal">Max Spent Monthly <?php echo form_error('max_spent_monthly') ?></label>
		<input type="text" class="form-control" name="max_spent_monthly" id="max_spent_monthly"
			   placeholder="Max Spent Monthly" value="<?php echo $max_spent_monthly; ?>"/>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('master/mst_house_use') ?>" class="btn btn-default">Cancel</a>
</form>
