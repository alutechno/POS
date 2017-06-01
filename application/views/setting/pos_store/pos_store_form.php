<form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
		<label for="varchar">Store Name <?php echo form_error('store_name') ?></label>
		<input type="text" class="form-control" name="store_name" id="store_name"
			   placeholder="Store Name" value="<?php echo $store_name; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Email <?php echo form_error('email') ?></label>
		<input type="text" class="form-control" name="email" id="email" placeholder="Email"
			   value="<?php echo $email; ?>"/>
	</div>
	<div class="form-group">
		<label for="varchar">Phone <?php echo form_error('phone') ?></label>
		<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone"
			   value="<?php echo $phone; ?>"/>
	</div>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('setting/pos_store') ?>" class="btn btn-default">Cancel</a>
</form>
