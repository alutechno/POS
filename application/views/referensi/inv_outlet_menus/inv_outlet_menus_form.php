<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
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
		<label for="exampleInputFile">Picture Menu</label>
		<input type="file" id="file" name="file"><img src="<?= base_url() ?>menu/<?= $image ?>"
													  width='180' height='120'>
	</div>

	<div class="form-group">
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   placeholder="Description" value="<?php echo $description; ?>"/>
	</div>

	<div class="form-group">
		<label for="varchar">Outlet <?php echo form_error('outlet_id') ?></label>
		<select class="form-control" name="outlet_id" id="outlet_id">
			<?php
			$query = $this->db->query("select * from mst_outlet");
			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $outlet_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<!-- <div class="form-group">
            <label for="varchar">Status <?php // echo form_error('status') ?></label>
            <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" />
        </div>-->
	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<select class="form-control" name="status" id="status">
			<?php
			$query = $this->db->query("select * from table_ref where table_name = 'inv_outlet_menus' and column_name = 'status' and value<>2");

			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->value ?>' <?php echo $row->value == $status ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<div class="form-group">
		<label for="int">Menu Class Id <?php echo form_error('menu_class_id') ?></label>
		<input type="text" class="form-control" name="menu_class_id" id="menu_class_id"
			   placeholder="Menu Class Id" value="<?php echo $menu_class_id; ?>"/>
	</div>
	<!--<div class="form-group">
            <label for="int">Menu Group Id <?php echo form_error('menu_group_id') ?></label>
            <input type="text" class="form-control" name="menu_group_id" id="menu_group_id" placeholder="Menu Group Id" value="<?php echo $menu_group_id; ?>" />
        </div>-->

	<div class="form-group">
		<label for="varchar">Menu Group Id <?php echo form_error('menu_group_id') ?></label>

		<select class="form-control" name="menu_group_id" id="menu_group_id">
			<?php
			$query = $this->db->query("select * from ref_outlet_menu_group ");

			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $menu_group_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<div class="form-group">
		<label for="decimal">Menu Price <?php echo form_error('menu_price') ?></label>
		<input type="text" class="form-control" name="menu_price" id="menu_price"
			   placeholder="Menu Price" value="<?php echo $menu_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Unit Cost <?php echo form_error('unit_cost') ?></label>
		<input type="text" class="form-control" name="unit_cost" id="unit_cost"
			   placeholder="Unit Cost" value="<?php echo $unit_cost; ?>"/>
	</div>

	<div class="form-group">
		<label for="varchar">Product Id <?php echo form_error('product_id') ?></label>
		<select class="form-control" name="product_id" id="product_id">
			<?php
			$query = $this->db->query("select * from mst_product");
			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $product_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<!--  <div class="form-group">
            <label for="int">Recipe Id <?php //echo form_error('recipe_id') ?></label>
            <input type="text" class="form-control" name="recipe_id" id="recipe_id" placeholder="Recipe Id" value="<?php //echo $recipe_id; ?>" />
        </div>-->

	<div class="form-group">
		<label for="varchar">Recipe Id <?php echo form_error('recipe_id') ?></label>
		<select class="form-control" name="recipe_id" id="recipe_id">
			<?php
			$query = $this->db->query("select * from mst_cuisine_recipe");
			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $recipe_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<div class="form-group">
		<label for="int">Recipe Qty <?php echo form_error('recipe_qty') ?></label>
		<input type="text" class="form-control" name="recipe_qty" id="recipe_qty"
			   placeholder="Recipe Qty" value="<?php echo $recipe_qty; ?>"/>
	</div>

	<!--<div class="form-group">
            <label for="varchar">Is Promo Enabled <?php //echo form_error('is_promo_enabled') ?></label>
            <input type="text" class="form-control" name="is_promo_enabled" id="is_promo_enabled" placeholder="Is Promo Enabled" value="<?php //echo $is_promo_enabled; ?>" />
        </div>-->

	<div class="form-group">
		<label for="varchar">Is Promo Enabled <?php echo form_error('is_promo_enabled') ?></label>
		<select class="form-control" name="is_promo_enabled" id="is_promo_enabled">
			<option value='Y' <? echo $is_promo_enabled == 'Y' ? 'selected' : ''; ?>>YES</option>
			<option value='N' <? echo $is_promo_enabled == 'N' ? 'selected' : ''; ?>>NO</option>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Is Export Cost <?php echo form_error('is_export_cost') ?></label>
		<select class="form-control" name="is_export_cost" id="is_export_cost">
			<option value='Y' <?php echo $is_export_cost == 'Y' ? 'selected' : ''; ?>YES
			</option>
			<option value='N' <?php echo $is_export_cost == 'N' ? 'selected' : ''; ?>NO
			</option>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Is Print After
			Total <?php echo form_error('is_print_after_total') ?></label>
		<select class="form-control" name="is_print_after_total" id="is_print_after_total">
			<option value='Y' <?php echo $is_print_after_total == 'Y' ? 'selected' : ''; ?>YES
			</option>
			<option value='N' <?php echo $is_print_after_total == 'N' ? 'selected' : ''; ?>NO
			</option>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Is Disable Change
			Price <?php echo form_error('is_disable_change_price') ?></label>
		<select class="form-control" name="is_disable_change_price" id="is_disable_change_price">
			<option value='Y' <? echo $is_disable_change_price == 'Y' ? 'selected' : ''; ?>>YES
			</option>
			<option value='N' <? echo $is_disable_change_price == 'N' ? 'selected' : ''; ?>>NO
			</option>
		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Print Kitchen Id <?php echo form_error('print_kitchen_id') ?></label>
		<select class="form-control" name="print_kitchen_id" id="print_kitchen_id">
			<?php
			$query = $this->db->query("select * from mst_kitchen");
			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $print_kitchen_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<div class="form-group">
		<label for="varchar">Print Kitchen
			Id <?php echo form_error('print_kitchen_section_id') ?></label>
		<select class="form-control" name="print_kitchen_section_id" id="print_kitchen_section_id">
			<?php
			$query = $this->db->query("select * from mst_kitchen_section");
			foreach ($query->result() as $row) {
				?>
				<option
					value='<?php echo $row->id ?>' <?php echo $row->id == $print_kitchen_id ? 'selected' : ''; ?>><?php echo $row->name ?></option>
				<?php
			}
			?>

		</select>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('referensi/inv_outlet_menus') ?>"
	   class="btn btn-default">Cancel</a>
</form>
