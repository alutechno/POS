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
	$query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from     pos_outlet_order_detil 
                                        where is_void=0 and table_id=" . $this->uri->segment(3) . " and outlet_id=" . $this->session->userdata('outlet') . " group by menu_id ");
	//	echo $this->db->last_query();
	// $query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from pos_outlet_order_detil
	//where is_void=0 and order_no=".$this->session->userdata('no_bill')." group by menu_id ");
	//echo $this->db->last_query();
	$i = 1;
	foreach ($query->result() as $row) {
		?>
		<tr>
			<td><?php echo $i ?></td>
			<td><?php echo $this->global_model->get_menu_name($row->menu_id) ?></td>
			<td><?php echo $row->qty ?></td>
			<td align="right">
				<?php echo number_format($row->amount) ?>
			</td>
		</tr>
		<?php
		$tax += $row->tax;
		$service += $row->service;
		$amount += $row->amount;
		$i++;
	}
	?>

	<tr>
		<td colspan="3" align="left">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="3" align="left">Total Food</td>
		<td align="right"><?php echo number_format($this->global_model->get_tot_food($this->session->table, $this->session->userdata('no_bill'))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Diskon Food</td>
		<td align="right">0</td>
	</tr>
	<tr>
		<td colspan="3" align="left">Total Beverages</td>
		<td align="right"><?php echo number_format($this->global_model->get_tot_beverages($this->session->table, $this->session->userdata('no_bill'))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Discount Beverages</td>
		<td align="right">0</td>
	</tr>
	<tr>
		<td colspan="3" align="left">Total Tax</td>
		<td align="right"><?php echo number_format($tax) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Service</td>
		<td align="right"><?php echo number_format($service) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Grand Total</td>
		<td align="right"><?php echo number_format($tax + $amount + $service) ?></td>
	</tr>
</table>
