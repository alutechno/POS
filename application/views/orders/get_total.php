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
		$order_id=$this->uri->segment(3);
		$order_id=explode( '-', $order_id);
		$order_id=implode(",",$order_id);
		$query = $this->db->query("select c.name,sum(b.order_qty) qty,sum(b.price_amount) price
			from pos_orders a,pos_orders_line_item b,inv_outlet_menus c
			where a.id=b.order_id
			and b.outlet_menu_id=c.id
			and b.serving_status<>'4'
			and a.id in (".$order_id.")
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
		$query = $this->db->query("select tax_id,sum(a.tax_amount) tax_amount,b.name tax_name
			from pos_order_taxes a,mst_pos_taxes b
			where a.tax_id=b.id
			and a.order_id in (".$order_id.")
			group by tax_id");
		$data=[];
		foreach ($query->result() as $row) {
			$data[$row->tax_name]=$row->tax_amount;
			$amount+=$row->tax_amount;
		}

		$query = $this->db->query("select b.name,sum(price_amount)price,IFNULL(sum(c.discount_amount),0) discount
			from pos_orders_line_item a left join pos_patched_discount c on a.id=c.order_line_item_id,ref_outlet_menu_class b
			where a.menu_class_id=b.id
			and a.order_id in (".$order_id.")
			group by b.name");
		$class=[];
		foreach ($query->result() as $row) {
			$class[$row->name]=array($row->price,$row->discount);
			$amount-=$row->discount;
		}
	?>

	<tr>
		<td colspan="3" align="left">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>

	<?php foreach($class as $key => $value){
		?>
	<tr>
		<td colspan="3" align="left">Total <?php echo $key?></td>
		<td align="right"><?php echo number_format($value[0]) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="left">Discount <?php echo $key?></td>
		<td align="right"><?php echo number_format($value[1]) ?></td>
	</tr>
	<?php }?>

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
