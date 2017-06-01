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
		<label for="varchar">Cooking Direction <?php echo form_error('cooking_direction') ?></label>
		<input type="text" class="form-control" name="cooking_direction" id="cooking_direction"
			   placeholder="Cooking Direction" value="<?php echo $cooking_direction; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Picture Link <?php echo form_error('picture_link') ?></label>
		<input type="text" class="form-control" name="picture_link" id="picture_link"
			   placeholder="Picture Link" value="<?php echo $picture_link; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<input type="text" class="form-control" name="status" id="status" placeholder="Status"
			   value="<?php echo $status; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Cost Center Id <?php echo form_error('cost_center_id') ?></label>
		<input type="text" class="form-control" name="cost_center_id" id="cost_center_id"
			   placeholder="Cost Center Id" value="<?php echo $cost_center_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Meal Time Id <?php echo form_error('meal_time_id') ?></label>
		<input type="text" class="form-control" name="meal_time_id" id="meal_time_id"
			   placeholder="Meal Time Id" value="<?php echo $meal_time_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Reg Style Id <?php echo form_error('reg_style_id') ?></label>
		<input type="text" class="form-control" name="reg_style_id" id="reg_style_id"
			   placeholder="Reg Style Id" value="<?php echo $reg_style_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Category Id <?php echo form_error('category_id') ?></label>
		<input type="text" class="form-control" name="category_id" id="category_id"
			   placeholder="Category Id" value="<?php echo $category_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Producing Qty <?php echo form_error('producing_qty') ?></label>
		<input type="text" class="form-control" name="producing_qty" id="producing_qty"
			   placeholder="Producing Qty" value="<?php echo $producing_qty; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Unit Type Id <?php echo form_error('unit_type_id') ?></label>
		<input type="text" class="form-control" name="unit_type_id" id="unit_type_id"
			   placeholder="Unit Type Id" value="<?php echo $unit_type_id; ?>"/>
	</div>
	<div class="form-group">
		<label for="int">Level <?php echo form_error('level') ?></label>
		<input type="text" class="form-control" name="level" id="level" placeholder="Level"
			   value="<?php echo $level; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Product Cost <?php echo form_error('product_cost') ?></label>
		<input type="text" class="form-control" name="product_cost" id="product_cost"
			   placeholder="Product Cost" value="<?php echo $product_cost; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Product Price <?php echo form_error('product_price') ?></label>
		<input type="text" class="form-control" name="product_price" id="product_price"
			   placeholder="Product Price" value="<?php echo $product_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Makeup Cost
			Percent <?php echo form_error('makeup_cost_percent') ?></label>
		<input type="text" class="form-control" name="makeup_cost_percent" id="makeup_cost_percent"
			   placeholder="Makeup Cost Percent" value="<?php echo $makeup_cost_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Expected Cost
			Percent <?php echo form_error('expected_cost_percent') ?></label>
		<input type="text" class="form-control" name="expected_cost_percent"
			   id="expected_cost_percent" placeholder="Expected Cost Percent"
			   value="<?php echo $expected_cost_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Selling Price <?php echo form_error('selling_price') ?></label>
		<input type="text" class="form-control" name="selling_price" id="selling_price"
			   placeholder="Selling Price" value="<?php echo $selling_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Last Unit Cost <?php echo form_error('last_unit_cost') ?></label>
		<input type="text" class="form-control" name="last_unit_cost" id="last_unit_cost"
			   placeholder="Last Unit Cost" value="<?php echo $last_unit_cost; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Last Cost Percent <?php echo form_error('last_cost_percent') ?></label>
		<input type="text" class="form-control" name="last_cost_percent" id="last_cost_percent"
			   placeholder="Last Cost Percent" value="<?php echo $last_cost_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Last Suggestion
			Price <?php echo form_error('last_suggestion_price') ?></label>
		<input type="text" class="form-control" name="last_suggestion_price"
			   id="last_suggestion_price" placeholder="Last Suggestion Price"
			   value="<?php echo $last_suggestion_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Onhand Unit Cost <?php echo form_error('onhand_unit_cost') ?></label>
		<input type="text" class="form-control" name="onhand_unit_cost" id="onhand_unit_cost"
			   placeholder="Onhand Unit Cost" value="<?php echo $onhand_unit_cost; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Onhand Cost
			Percent <?php echo form_error('onhand_cost_percent') ?></label>
		<input type="text" class="form-control" name="onhand_cost_percent" id="onhand_cost_percent"
			   placeholder="Onhand Cost Percent" value="<?php echo $onhand_cost_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Onhand Suggestion
			Price <?php echo form_error('onhand_suggestion_price') ?></label>
		<input type="text" class="form-control" name="onhand_suggestion_price"
			   id="onhand_suggestion_price" placeholder="Onhand Suggestion Price"
			   value="<?php echo $onhand_suggestion_price; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Manual Unit Cost <?php echo form_error('manual_unit_cost') ?></label>
		<input type="text" class="form-control" name="manual_unit_cost" id="manual_unit_cost"
			   placeholder="Manual Unit Cost" value="<?php echo $manual_unit_cost; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Manual Cost
			Percent <?php echo form_error('manual_cost_percent') ?></label>
		<input type="text" class="form-control" name="manual_cost_percent" id="manual_cost_percent"
			   placeholder="Manual Cost Percent" value="<?php echo $manual_cost_percent; ?>"/>
	</div>
	<div class="form-group">
		<label for="decimal">Manual Suggestion
			Price <?php echo form_error('manual_suggestion_price') ?></label>
		<input type="text" class="form-control" name="manual_suggestion_price"
			   id="manual_suggestion_price" placeholder="Manual Suggestion Price"
			   value="<?php echo $manual_suggestion_price; ?>"/>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo site_url('mst_cuisine_recipe') ?>" class="btn btn-default">Cancel</a>
</form>
