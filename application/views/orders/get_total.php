<table class="table table-striped">
	<tr>
		<th style="width: 10px">No</th>
		<th style="width: 180px">Menu</th>
		<th>Qty</th>
		<th>Amount</th>

	</tr>

	<?php
		$tax = 0;
		$service = 0;
		$amount = 0;
		//$query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from     pos_outlet_order_detil
        //    where is_void=0 and table_id=" . $this->uri->segment(3) . " and outlet_id=" . $this->session->userdata('outlet') . " group by menu_id ");

		$query = $this->db->query("select c.name,sum(b.order_qty) qty,sum(b.price_amount) price
			from pos_orders a,pos_orders_line_item b,inv_outlet_menus c
			where a.id=b.order_id
			and b.outlet_menu_id=c.id
			and a.id=".$this->uri->segment(3)."
			group by c.name");
		$i = 1;
		//echo $query;
		foreach ($query->result() as $row) {
			?>
			<tr>
				<td><?php echo $i ?></td>
				<td><?php echo $row->name ?></td>
				<td><?php echo $row->qty ?></td>
				<td align="right">
					<?php echo number_format($row->price) ?>
				</td>
			</tr>
			<?php
			$amount += $row->price;
			$i++;
		}
		$query = $this->db->query("select tax_id,a.tax_amount,b.name tax_name
			from pos_order_taxes a,mst_pos_taxes b
			where a.tax_id=b.id
			and a.order_id=".$this->uri->segment(3));
		$data=[];
		foreach ($query->result() as $row) {
			$data[$row->tax_name]=$row->tax_amount;
			$amount+=$row->tax_amount;
		}
	?>

	<tr>
		<td colspan="3" align="left">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="3" align="left">Total Food</td>
		<td align="right"><?php echo number_format($this->global_model->get_tot_food($this->session->table,
																					 $this->session->userdata('no_bill'))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Diskon Food</td>
		<td align="right">0</td>
	</tr>
	<tr>
		<td colspan="3" align="left">Total Beverages</td>
		<td align="right"><?php echo number_format($this->global_model->get_tot_beverages($this->session->table,
																						  $this->session->userdata('no_bill'))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Discount Beverages</td>
		<td align="right">0</td>
	</tr>
	<?php foreach($data as $key => $value){
		?>
	<tr>
		<td colspan="3" align="left">Total <?php echo $key?></td>
		<td align="right"><?php echo number_format($value) ?></td>
	</tr>
	<?php }?>
	<!--<tr>
		<td colspan="3" align="left">Service</td>
		<td align="right"><?php echo number_format($service) ?></td>
	</tr>-->
	<tr>
		<td colspan="3" align="left">Grand Total</td>
		<td align="right"><?php echo number_format($tax + $amount + $service) ?></td>
	</tr>
</table>
