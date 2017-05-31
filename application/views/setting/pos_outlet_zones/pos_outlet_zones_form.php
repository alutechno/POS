
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Store Id <?php echo form_error('store_id') ?></label>
            
           
        <div class="form-group">
          
             <select class="form-control" name="store_id" id="store_id">
            <?
            $query = $this->db->query("select * from mst_outlet");
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?=$row->id?>' <? echo $row->id== $store_id?'selected':'';?>><?=$row->name?></option>
              <?
              }
              ?>
            </select>
        </div>
            </div>
	    <div class="form-group">
            <label for="varchar">Zone Name <?php echo form_error('name') ?></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo base_url('setting/pos_outlet_zones') ?>" class="btn btn-default">Cancel</a>
	</form>
