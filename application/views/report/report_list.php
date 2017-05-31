 <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Select Period Report</h4>
                    </div>
                    <form action="<?php echo base_url()?>report/report_list/show" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="reportid" id="reportid" value=""/>
                                    <div class="form-group">
                                        <label>Report:</label>
                                        <div class="input-group">
                                         <div class="input-group-addon">
                                                <i class="fa fa-file"></i>
                                            </div>
                                            <input type="text" name="reportname" id="reportname" class="form-control pull-right" id="reservation"/>
                                        </div><!-- /.input group -->
                                    </div><!-- /.form group -->
                            <div class="form-group">
                                        <label>Date Period:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="period" class="form-control pull-right" id="reservation"/>
                                        </div><!-- /.input group -->
                                    </div><!-- /.form group -->
                        </div>
                        <div class="modal-footer clearfix">
                            <button type="submit" class="btn btn-primary pull-left">Show</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
<!-- START TYPOGRAPHY -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-solid">
                
                                <div class="box-body">
                                    <?php 
                                    $query = $this->db->query("select * from mst_pos_report");

                                    foreach ($query->result() as $row)
                                    {
                                    ?>
                                    <h5><a href="#" data-toggle="modal"  data-target="#compose-modal" onclick="parsing('<?php echo $row->report_file?>','<?php echo $row->report_name?>')"><?php echo $row->report_name?></a></h5>
                                    <?php
                                    }
                                    ?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- ./col -->
 
                    </div><!-- /.row -->