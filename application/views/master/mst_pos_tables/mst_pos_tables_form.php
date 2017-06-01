<form action="<?php echo $action; ?>" method="post">
	<!--<div class="form-group">
            <label for="int">Outlet Id <?php // echo form_error('outlet_id') ?></label>
            <input type="text" class="form-control" name="outlet_id" id="outlet_id" placeholder="Outlet Id" value="<?php echo $outlet_id; ?>" />
        </div>-->

	<div class="form-group">
		<label for="int">Outlet Id <?php echo form_error('outlet_id') ?></label>
		<select class="form-control" name="outlet_id" id="outlet_id">
			<?php
			$query = $this->db->query("select * from mst_outlet where status=1");
			foreach ($query->result() as $row) {
				?>
				<option value='<?= $row->id ?>'><?= $row->name ?></option>
				<?php
			}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Table No <?php echo form_error('table_no') ?></label>
		<input type="text" class="form-control" name="table_no" id="table_no" placeholder="Table No"
			   value="<?php echo $table_no; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Cover <?php echo form_error('cover') ?></label>
		<input type="text" class="form-control" name="cover" id="cover" placeholder="Cover"
			   value="<?php echo $cover; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Section <?php echo form_error('section') ?></label>
		<input type="text" class="form-control" name="section" id="section" placeholder="Section"
			   value="<?php echo $section; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Remark <?php echo form_error('remark') ?></label>
		<input type="text" class="form-control" name="remark" id="remark" placeholder="Remark"
			   value="<?php echo $remark; ?>"/>
	</div>

	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<select class="form-control" name="status" id="status">
			<option value='1'>Active</option>
			<option value='0'>Not Active</option>

		</select>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo site_url('mst_pos_tables') ?>" class="btn btn-default">Cancel</a>
</form>
