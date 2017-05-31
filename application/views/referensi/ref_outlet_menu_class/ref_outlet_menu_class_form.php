
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
	    <!--<div class="form-group">
            <label for="varchar">Status <?php// echo form_error('status') ?></label>
            <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" />
        </div>-->
            
                   <div class="form-group">
            <label for="varchar">Status <?php echo form_error('status') ?></label>
             <select class="form-control" name="status" id="status">
            <?
            $query = $this->db->query("select * from table_ref where table_name = 'ref_outlet_menu_class' and column_name = 'status' and value<>2");
            
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?=$row->value?>' <? echo $row->value== $status?'selected':'';?>><?=$row->name?></option>
             <?
            }
             ?>
            
             </select>
        </div>
	   
            
	    <div class="form-group">
            <label for="int">Parent Class Id <?php echo form_error('parent_class_id') ?></label>
            <input type="text" class="form-control" name="parent_class_id" id="parent_class_id" placeholder="Parent Class Id" value="<?php echo $parent_class_id; ?>" />
        </div>
	  
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo base_url('referensi/ref_outlet_menu_class') ?>" class="btn btn-default">Cancel</a>
	</form>
