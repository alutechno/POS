 <!-- START CUSTOM TABS -->
                   <br/>
                     <div class="row">
                    
                  
                          <div class="col-md-8">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                               <!-- <ul class="nav nav-tabs">
                       
                                 <li class="active"><a href="#tab_1" data-toggle="tab"><?//=$row->name?></a></li>
                                <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                         
                                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                                </ul>-->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        

 <!-- Main content -->
                <section class="content">

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <?php
                        //select a.* from mst_pos_tables a left join pos_orders b on a.id=b.table_id
                        //$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=".$this->session->userdata('outlet')."");

                            foreach ($query as $row)
                            {
                        ?>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-aqua">
                                <a href="<?=base_url()?>main/inputpesan/<?=$row->id?>/<?=$row->menu_price?>/<?=$row->menu_class_id?>" class="small-box-footer">&nbsp;<center><img src="<?=base_url()?>menu/<?=$row->image?>" width="160" height="80"> </center><center> <?=$row->short_name?></center>
                                
                                   <?= number_format($row->menu_price)?> 
                                </a>
                            </div>
                        </div>
                        
                        <?
                        }
                        ?>
                        

   
                    </div><!-- /.row -->

                    <!-- top row -->
                    <div class="row">
                        <div class="col-xs-12 connectedSortable">
                            
                        </div><!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-6 connectedSortable"> 
                            <!-- Box (with bar chart) -->

                        </section><!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                 
                    </div><!-- /.row (main row) -->

                </section>
                                    </div><!-- /.tab-pane -->
                                  
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->

                        
                            <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title">#Table No = <?=$this->uri->segment(3);?></h3>
                                    </br> 
                                
                                    
                                              
                                </div><!-- /.box-header -->
                                    <a class="btn btn-app" href='<?=base_url('main')?>'>
                                        <i class="fa fa-home"></i> Home
                                    </a>
                                <div class="box-body text-center">
                                   
                                    <iframe width="360" frameBorder="0" height="415" src="<?=base_url()?>main/payment_total/<?=$this->uri->segment(3)?>" allowfullscreen></iframe>                
                                        <!-- end -->
                                    <a class="btn btn-app bg-red" href='<?=base_url()?>main/cancel_order' onclick="javasciprt: return confirm('Are you Sure Cancel This Order ?')">
                                        <i class="fa fa-times"></i>Cancel Order</a>
                                    </a>
                                    
                                        <a class="btn btn-app bg-yellow" onclick="print(<?=$this->session->order_no;?>)">
                                        <i class="fa fa-print"></i>Print Order</a>
                                    </a>
                                    <a class="btn btn-app bg-green">
                                        <i class="fa fa-money" onclick="print_payment(<?=$this->session->order_no;?>)"></i> Payment
                                    </a>
                                      <a class="btn btn-app bg-yellow" href='<?=base_url('main')?>'>
                                        <i class="fa fa-edit"></i> Note
                                    </a>
                                 
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col (left) -->
                        
                    </div><!-- /.row -->
                    
               
                        
 <script type="text/javascript">
  
   function print(order)
  {
        PopupCenter('<?=REPORT_BIRT?>struk_kitchen.rptdesign&__format=pdf&no_bill=<?=$this->session->userdata('no_bill')?>&table=<?=$this->uri->segment(3)?>&date=<?=date('Y-M-d')?>&outlet=<?=$this->session->userdata('outlet')?>&waitress=<?=$this->session->userdata('name')?>','xtf','600','600');
        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close(); 
  }
  
     function print_payment(order)
  {
        PopupCenter('<?=REPORT_BIRT?>struk_order.rptdesign&__format=pdf&no_bill=<?=$this->global_model->get_no_bill($this->uri->segment(3))?>&table=<?=$this->uri->segment(3)?>&date=<?=date('Y-M-d')?>&outlet=<?=$this->session->userdata('outlet')?>&waitress=<?=$this->session->userdata('name')?>','xtf','600','600');
        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close(); 
  }
  
  function PopupCenter(url, title, w, h) {
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}


function cancel()
{
    alert('Cancel This Order?');
}
</script>
                        