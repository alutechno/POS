<body onload="window.print()"> 
<table class="table table-striped">
                                        <tr>
                                            <th style="width: 10px">No</th>
                                            <th style="width: 180px">Menu</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            
                                        </tr>
                                        
                                        <?
                                        $query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty from pos_outlet_order_detil 
                                        where order_no=".$this->session->order_no." group by menu_id ");
                                            $i=1;
                                            foreach ($query->result() as $row)
                                            {
                                        ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$row->menu_id?>-Ikan panggang</td>
                                            <td><?=$row->qty?></td>
                                            <td>
                                              <?= number_format($row->amount)?>
                                            </td>
                                           
                                        </tr>
                                       <?
                                       $i++;
                                       }
                                       ?>
                                    </table>
    </body>