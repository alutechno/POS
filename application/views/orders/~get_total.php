 <table class="table table-striped">
                                        <tr>
                                            <th style="width: 10px">No</th>
                                            <th style="width: 180px">Menu</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            
                                        </tr>
                                        
                                        <?php
                                        $tax = 0;
                                        $amount=0;
                                        $query = $this->db->query("select * from pos_orders_line_item 
                                        where order_id=".$this->session->userdata('no_bill')." group by id ");
                                            $i=1;
                                            foreach ($query->result() as $row)
                                            {
                                        ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$row->menu_id?>-Ikan panggang</td>
                                            <td><?=$row->qty?></td>
                                            <td align="right">
                                              <?= number_format($row->amount)?>
                                            </td>
                                           
                                        </tr>
                                       <?
                                        $tax += $row->tax;
                                        $amount +=$row->amount;
                                       $i++;
                                       }
                                       ?>
                                        <tr>
                                        <td colspan="3" align="left">&nbsp;</td>
                                        <td  align="right">&nbsp;</td>
                                       </tr>
                                       
                                        <tr>
                                        <td colspan="3" align="left">Total Food</td>
                                        <td  align="right">0</td>
                                       </tr>
                                           <tr>
                                        <td colspan="3" align="left">Diskon Food</td>
                                        <td  align="right">0</td>
                                       </tr>
                                       <tr>
                                        <td colspan="3" align="left">Total Beverages</td>
                                        <td  align="right">0</td>
                                       </tr>
                                       <tr>
                                        <td colspan="3" align="left">Discount Beverages</td>
                                        <td  align="right">0</td>
                                       </tr>
                                       
                                       <tr>
                                        <td colspan="3" align="left">Total Tax</td>
                                        <td  align="right"><?=number_format($tax)?></td>
                                       </tr>
                                        <tr>
                                        <td colspan="3" align="left">Grand Total </td>
                                        <td  align="right"><?=number_format($tax+$amount)?></td>
                                       </tr>
                                    </table>