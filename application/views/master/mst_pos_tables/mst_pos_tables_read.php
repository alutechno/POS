<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Mst_pos_tables Read</h2>
        <table class="table">
	    <tr><td>Outlet Id</td><td><?php echo $outlet_id; ?></td></tr>
	    <tr><td>Table No</td><td><?php echo $table_no; ?></td></tr>
	    <tr><td>Cover</td><td><?php echo $cover; ?></td></tr>
	    <tr><td>Section</td><td><?php echo $section; ?></td></tr>
	    <tr><td>Remark</td><td><?php echo $remark; ?></td></tr>
	    <tr><td>Status</td><td><?php echo $status; ?></td></tr>
	    <tr><td>Created Date</td><td><?php echo $created_date; ?></td></tr>
	    <tr><td>Modified Date</td><td><?php echo $modified_date; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Modified By</td><td><?php echo $modified_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('mst_pos_tables') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>