<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="varchar">Table Number <?php echo form_error('name') ?></label>
		<input type="text" class="form-control" name="name" id="name" placeholder="Number"
			   value="<?php echo $name; ?>" autocomplete="off"/>
	</div>

	<div class="form-group">
		<label for="int">Zone <?php echo form_error('zone_id') ?></label>
		<select class="form-control" name="zone_id" id="zone_id">
			<?php
				$query = $this->db->query("select a.*,b.name as outlet from pos_outlet_zones a inner join mst_outlet b on b.id=a.store_id");
				foreach ($query->result() as $row) {
					?>
					<option
						value='<?php echo $row->id ?>' <?php echo $row->id == $zone_id ? 'selected' : ''; ?>><?php echo $row->name ?>
						-<?php echo $row->outlet ?> </option>
					<?php
				}
			?>
		</select>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('setting/pos_outlet_tables') ?>" class="btn btn-default">Cancel</a>
</form>
