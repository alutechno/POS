
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
            <label for="varchar">Description <?php echo form_error('description') ?></label>
            <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="<?php echo $description; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Status <?php echo form_error('status') ?></label>
            <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Food <?php echo form_error('food') ?></label>
            <input type="text" class="form-control" name="food" id="food" placeholder="Food" value="<?php echo $food; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Beverage <?php echo form_error('beverage') ?></label>
            <input type="text" class="form-control" name="beverage" id="beverage" placeholder="Beverage" value="<?php echo $beverage; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Others <?php echo form_error('others') ?></label>
            <input type="text" class="form-control" name="others" id="others" placeholder="Others" value="<?php echo $others; ?>" />
        </div>
	<!--    <div class="form-group">
            <label for="timestamp">Created Date <?php echo form_error('created_date') ?></label>
            <input type="text" class="form-control" name="created_date" id="created_date" placeholder="Created Date" value="<?php echo $created_date; ?>" />
        </div>
	    <div class="form-group">
            <label for="timestamp">Modified Date <?php echo form_error('modified_date') ?></label>
            <input type="text" class="form-control" name="modified_date" id="modified_date" placeholder="Modified Date" value="<?php echo $modified_date; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Created By <?php echo form_error('created_by') ?></label>
            <input type="text" class="form-control" name="created_by" id="created_by" placeholder="Created By" value="<?php echo $created_by; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Modified By <?php echo form_error('modified_by') ?></label>
            <input type="text" class="form-control" name="modified_by" id="modified_by" placeholder="Modified By" value="<?php echo $modified_by; ?>" />
        </div>-->
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo base_url('master/mst_pos_discount') ?>" class="btn btn-default">Cancel</a>
	</form>
