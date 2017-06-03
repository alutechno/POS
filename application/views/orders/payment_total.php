<table class="table table-striped">
	<tr>

		<th colspan="2" style="width: 180px">Menu</th>
		<th>Qty</th>
		<th>Amount</th>

	</tr>

	<?php
		$tax = 0;
		$amount = 0;
		$service = 0;
		$query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from pos_outlet_order_detil 
                                        where is_void=0 and table_id=" . $this->uri->segment(3) . " and outlet_id=" . $this->session->userdata('outlet') . " group by menu_id ");
		$i = 1;
		foreach ($query->result() as $row) {
			?>
			<tr>

				<td colspan="2"> <?php echo $this->global_model->get_menu_name($row->menu_id) ?></td>
				<td> <?php echo $row->qty ?></td>
				<td align="right">
					<?php echo number_format($row->amount) ?>
				</td>
			</tr>
			<?php
			$tax += $row->tax;
			$amount += $row->amount;
			$service += $row->service;
			$i++;
		}
	?>

	<?php
		$query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from pos_outlet_order_detil 
                                        where is_void=1 and table_id=" . $this->uri->segment(3) . " and outlet_id=" . $this->session->userdata('outlet') . " group by menu_id ");
		foreach ($query->result() as $row2) {
			?>
			<tr>
				<td colspan="2"> <?php echo $this->global_model->get_menu_name($row2->menu_id) ?></td>
				<td> <?php echo $row2->qty ?></td>
				<td align="right">
					<?php echo number_format($row2->amount) ?>-V
				</td>

			</tr>
			<?php
		}
	?>
	<tr>
		<td colspan="3" align="left">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="3" align="left">Total Food</td>
		<td align="right"> <?php echo number_format($this->global_model->get_tot_food($this->uri->segment(3))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Diskon Food</td>
		<td align="right">0</td>
	</tr>
	<tr>
		<td colspan="3" align="left">Total Beverages</td>
		<td align="right"> <?php echo number_format($this->global_model->get_tot_beverages($this->uri->segment(3),
																						   $this->session->userdata('no_bill'))) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Discount Beverages</td>
		<td align="right">0</td>
	</tr>

	<tr>
		<td colspan="3" align="left">Total Tax</td>
		<td align="right"> <?php echo number_format($amount * 11 / 100) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Service</td>
		<td align="right"> <?php echo number_format($amount * 10 / 100) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Grand Total</td>
		<td align="right"> <?php echo number_format($tax + $amount + $service) ?></td>
	</tr>
</table>
