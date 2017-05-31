
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Code <?php echo form_error('code') ?></label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Code" value="<?php echo $code; ?>" />
       
             <label for="varchar">Name <?php echo form_error('name') ?></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
            
            <label for="varchar">Address <?php echo form_error('address') ?></label>
            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $address; ?>" />
            
            <label for="varchar">Bill Footer <?php echo form_error('bill_footer') ?></label>
            <input type="text" class="form-control" name="bill_footer" id="bill_footer" placeholder="Bill Footer" value="<?php echo $bill_footer; ?>" />
            
            
             <label for="varchar">Status <?php echo form_error('status') ?></label>
            
         <select class="form-control" name="status" id="status">
            <?php
            $query = $this->db->query("select *
                                    from table_ref
                                  where table_name = 'mst_outlet'
                                  and column_name = 'status'");
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?php echo $row->value?>' <?php echo $row->value== $status?'selected':'';?>><?php echo $row->name?> </option>
              <?php
              }
              ?>
            </select>
            
            
             <label for="int">Outlet Type Id <?php echo form_error('outlet_type_id') ?></label>
            
            
            <select class="form-control" name="outlet_type_id" id="outlet_type_id">
            <?php
            $query = $this->db->query("select *
                                    from ref_outlet_type
                                  ");
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?php echo $row->id?>' <?php echo $row->id== $outlet_type_id?'selected':'';?>><?php echo $row->name?> </option>
             <?php
              }
              ?>
            </select>
            
            
            <label for="int">Cost Center Id <?php echo form_error('cost_center_id') ?></label>
          
                  <select class="form-control" name="cost_center_id" id="cost_center_id">
            <?php
            $query = $this->db->query("select * from mst_cost_center");
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?php echo $row->id?>' <?php echo $row->id== $cost_center_id?'selected':'';?>><?php echo $row->name?> </option>
              <?php
            }
              ?>
            </select>
            
           
            <label for="varchar">Delivery Bill Footer <?php echo form_error('delivery_bill_footer') ?></label>
            <input type="text" class="form-control" name="delivery_bill_footer" id="delivery_bill_footer" placeholder="Delivery Bill Footer" value="<?php echo $delivery_bill_footer; ?>" />
            
            <label for="int">No Of Seats <?php echo form_error('no_of_seats') ?></label>
            <input type="text" class="form-control" name="no_of_seats" id="no_of_seats" placeholder="No Of Seats" value="<?php echo $no_of_seats; ?>" />
           
             <label for="int">M2 <?php echo form_error('m2') ?></label>
            <input type="text" class="form-control" name="m2" id="m2" placeholder="M2" value="<?php echo $m2; ?>" />
            
            <label for="int">Last Meal Period <?php echo form_error('last_meal_period') ?></label>
            <input type="text" class="form-control" name="last_meal_period" id="last_meal_period" placeholder="Last Meal Period" value="<?php echo $last_meal_period; ?>" />
            
             <label for="int">Curr Meal Period <?php echo form_error('curr_meal_period') ?></label>
             <input type="text" class="form-control" name="curr_meal_period" id="curr_meal_period" placeholder="Curr Meal Period" value="<?php echo $curr_meal_period; ?>" />
             
              <label for="int">List Number <?php echo form_error('list_number') ?></label>
             <input type="text" class="form-control" name="list_number" id="list_number" placeholder="List Number" value="<?php echo $list_number; ?>" />
             
             <label for="int">Num Of Employee <?php echo form_error('num_of_employee') ?></label>
             <input type="text" class="form-control" name="num_of_employee" id="num_of_employee" placeholder="Num Of Employee" value="<?php echo $num_of_employee; ?>" />
             
              <label for="varchar">Is Allow Cancel Tax <?php echo form_error('is_allow_cancel_tax') ?></label>
              <input type="text" class="form-control" name="is_allow_cancel_tax" id="is_allow_cancel_tax" placeholder="Is Allow Cancel Tax" value="<?php echo $is_allow_cancel_tax; ?>" />
              
              <label for="int">Fo Gl Journal Code <?php echo form_error('fo_gl_journal_code') ?></label>
               
            
            <select class="form-control" name="fo_gl_journal_code" id="fo_gl_journal_code">
            <?php
            $query = $this->db->query("select * from mst_guest_transaction_type");
            foreach ($query->result() as $row)
            {
            ?>
                <option value='<?php echo $row->id?>' <?php echo $row->id== $fo_gl_journal_code?'selected':'';?>><?php echo $row->code?>-<?php echo $row->folio_text?> </option>
              <?php
            }
              ?>
            </select>
               
            </div>

	   
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo base_url('setting/mst_outlet') ?>" class="btn btn-default">Cancel</a>
	</form>
