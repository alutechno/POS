
        <form action="<?php echo $action; ?>" method="post">

            
        <div class="form-group">
            <label for="int">Kitchen Id <?php echo form_error('kitchen_id') ?></label>
             <select class="form-control" name="kitchen_id" id="kitchen_id">
                 <?
                 $query = $this->db->query("select * from mst_kitchen");
                    foreach ($query->result() as $row)
                    {
                 ?>
                 <option value='<?=$row->id?>'><?=$row->name?></option>
                 <?
                   }
                 ?>
             </select>
        </div>
            
            
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
	   
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo base_url('master/mst_kitchen_section') ?>" class="btn btn-default">Cancel</a>
	</form>
