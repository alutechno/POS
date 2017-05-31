
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Code <?php echo form_error('code') ?></label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Code" value="<?php echo $code; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Name <?php echo form_error('name') ?></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Short Name <?php echo form_error('short_name') ?></label>
            <input type="text" class="form-control" name="short_name" id="short_name" placeholder="Short Name" value="<?php echo $short_name; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Description <?php echo form_error('description') ?></label>
            <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="<?php echo $description; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Status <?php echo form_error('status') ?></label>
            <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Percent Food <?php echo form_error('percent_food') ?></label>
            <input type="text" class="form-control" name="percent_food" id="percent_food" placeholder="Percent Food" value="<?php echo $percent_food; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Percent Beverage <?php echo form_error('percent_beverage') ?></label>
            <input type="text" class="form-control" name="percent_beverage" id="percent_beverage" placeholder="Percent Beverage" value="<?php echo $percent_beverage; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Percent Others <?php echo form_error('percent_others') ?></label>
            <input type="text" class="form-control" name="percent_others" id="percent_others" placeholder="Percent Others" value="<?php echo $percent_others; ?>" />
        </div>
	   
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('mst_pos_member_package') ?>" class="btn btn-default">Cancel</a>
	</form>
