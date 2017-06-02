<link href="<?php echo VIRTUAL_KEYBOARD ?>css/keyboard.css" rel="stylesheet">
<link href="<?php echo VIRTUAL_KEYBOARD ?>docs/css/jquery-ui.min.css" rel="stylesheet">
<script src="<?php echo VIRTUAL_KEYBOARD ?>docs/js/jquery-latest.min.js"></script>
<script src="<?php echo VIRTUAL_KEYBOARD ?>docs/js/jquery-ui.min.js"></script>
<script src="<?php echo VIRTUAL_KEYBOARD ?>js/jquery.keyboard.js"></script>

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
		<label for="varchar">Description <?php echo form_error('description') ?></label>
		<input type="text" class="form-control" name="description" id="description"
			   value="<?php echo $description; ?>"/>
	</div>

	<div class="form-group">
		<label for="varchar">Status <?php echo form_error('status') ?></label>
		<select class="form-control" name="status" id="status">
			<?
			$query = $this->db->query("select * from table_ref where table_name = 'ref_outlet_type' and column_name = 'status' and value<>2");

			foreach ($query->result() as $row) {
				?>
				<option
					value='<?= $row->value ?>' <? echo $row->value == $status ? 'selected' : ''; ?>><?= $row->name ?></option>
				<?
			}
			?>

		</select>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	<a href="<?php echo base_url('referensi/ref_outlet_type') ?>" class="btn btn-default">Cancel</a>
</form>
<script type="text/javascript">
$('input[type="text"]').keyboard({ layout: 'qwerty' });
</script>
