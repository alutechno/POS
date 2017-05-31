 <!-- START CUSTOM TABS -->
                   <br/>
                     <div class="row">
                    
                  
                          <div class="col-md-8">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Tab 1</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                         
                                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <table class="table table-bordered text-center">
                                        <tr>
                                            <th>Menu 1</th>
                                            <th>Menu 2</th>
                                             <th>Menu 3</th>
                                             <th>Menu 4</th>
                                             <th>Menu 5</th>
                                        </tr>
                                        <tr>
                                            <td><a  href="<?=base_url()?>pos/tables/inputpesan/<?=$this->session->table;?>/1" ><img src="<?=base_url()?>menu/1.jpeg" width="80" height="80"></a> </td>
                                            <td><a  href="<?=base_url()?>pos/tables/inputpesan/<?=$this->session->table;?>/2" ><img src="<?=base_url()?>menu/2.jpeg" width="80" height="80"></a> </td>
                                            <td><img src="<?=base_url()?>menu/3.jpeg" width="80" height="80"></td>
                                            <td><img src="<?=base_url()?>menu/4.jpg" width="80" height="80"></td>
                                            <td><img src="<?=base_url()?>menu/5.jpg" width="80" height="80"></td>
                                        </tr>
                                        <!--<tr>
                                            <td><button class="btn btn-primary">Primary</button></td>
                                            <td><button class="btn btn-primary btn-lg">Primary</button></td>
                                            <td><button class="btn btn-primary btn-sm">Primary</button></td>
                                            <td><button class="btn btn-primary btn-flat">Primary</button></td>
                                            <td><button class="btn btn-primary disabled">Primary</button></td>
                                        </tr>-->
                                        
                                    </table>
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">
                                        The European languages are members of the same family. Their separate existence is a myth.
                                        For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                                        in their grammar, their pronunciation and their most common words. Everyone realizes why a
                                        new common language would be desirable: one could refuse to pay expensive translators. To
                                        achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                                        words. If several languages coalesce, the grammar of the resulting language is more simple
                                        and regular than that of the individual languages.
                                    </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->

                        
                            <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title">Order Number: <?=$this->session->order_no;?> #Table Number = <?=$this->session->table;?></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body text-center">
                                  
                                 <!-- <table class="table table-striped">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Task</th>
                                            <th>Progress</th>
                                            <th style="width: 40px">Label</th>
                                        </tr>
                                        <tr>
                                            <td>1.</td>
                                            <td>Update software</td>
                                            <td>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-red">55%</span></td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Clean database</td>
                                            <td>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-yellow">70%</span></td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Cron job running</td>
                                            <td>
                                                <div class="progress xs progress-striped active">
                                                    <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light-blue">30%</span></td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Fix and squish bugs</td>
                                            <td>
                                                <div class="progress xs progress-striped active">
                                                    <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-green">90%</span></td>
                                        </tr>
                                    </table>-->
                                    <iframe width="360" frameBorder="0" height="415" src="<?=base_url()?>pos/tables/get_total" allowfullscreen></iframe>
                              
                                        <!-- total-->
                                       <!-- <table class="table table-striped">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Task</th>
                                            <th>Progress</th>
                                            <th style="width: 40px">Label</th>
                                        </tr>
                                        <tr>
                                            <td>1.</td>
                                            <td>Update software</td>
                                            <td>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-red">55%</span></td>
                                        </tr>
                                  
                                    </table>-->
                                        <!-- end -->
                                    <a class="btn btn-app" onclick="cancel(<?=$this->session->order_no;?>)">
                                        <i class="fa fa-times"></i>Cancel Order</a>
                                    </a>
                                    
                                        <a class="btn btn-app" onclick="print(<?=$this->session->order_no;?>)">
                                        <i class="fa fa-print"></i>Print Order</a>
                                    </a>
                                    <a class="btn btn-app">
                                        <i class="fa fa-edit"></i> Payment
                                    </a>
                                      <a class="btn btn-app">
                                        <i class="fa fa-home"></i> Home
                                    </a>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col (left) -->
                        
                    </div><!-- /.row -->
                    
               
                        
 <script type="text/javascript">
  function print(order)
  {
    //var myWindow=window.open('','','width=100,height=100');
    //myWindow.document.write("<p>This is 'myWindow'</p>");
    PopupCenter('<?=base_url()?>pos/tables/get_total_print','xtf','400','300');
     myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close();
    
  }
  
  function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
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

</script>
                        