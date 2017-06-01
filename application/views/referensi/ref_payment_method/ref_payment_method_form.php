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
		<label for="decimal">Credit Limit <?php echo form_error('credit_limit') ?></label>
		<input type="text" class="form-control" name="credit_limit" id="credit_limit"
			   placeholder="Credit Limit" value="<?php echo $credit_limit; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Is Credit Card <?php echo form_error('is_credit_card') ?></label>
		<input type="text" class="form-control" name="is_credit_card" id="is_credit_card"
			   placeholder="Is Credit Card" value="<?php echo $is_credit_card; ?>"/>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('referensi/ref_payment_method') ?>"
	   class="btn btn-default">Cancel</a>
</form>
