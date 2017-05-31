
        <form action="<?php echo $action; ?>" method="post">

             <div class="form-group">
            <label for="int">Segment Id <?php echo form_error('segment_id') ?></label>
             <select class="form-control" name="segment_id" id="segment_id">
                 <?php
                 $query = $this->db->query("select * from ref_pos_segment where status=1");

                    foreach ($query->result() as $row)
                    {
                 ?>
                                  <option value='<?php echo $row->id?>'><?php echo $row->name?></option>
                     <?php
                     }
                     ?>
                              </select>
        </div>
            
            
        <div class="form-group">
            <label for="int">Payment Id <?php echo form_error('payment_id') ?></label>
             <select class="form-control" name="payment_id" id="payment_id">
                 <?
                    $query = $this->db->query("select * from ref_payment_method where status=1 and  (outlet_type_id=1 or outlet_type_id=2)");
                    foreach ($query->result() as $row)
                    {
                 ?>
                                  <option value='<?=$row->id?>'><?=$row->name?></option>
                  <?
                     }
                  ?>
             </select>
        </div>
            
	    
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('ref_pos_segment_payment') ?>" class="btn btn-default">Cancel</a>
	</form>
