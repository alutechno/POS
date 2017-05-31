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
        <h2 style="margin-top:0px">Inv_outlet_menus Read</h2>
        <table class="table">
	    <tr><td>Code</td><td><?php echo $code; ?></td></tr>
	    <tr><td>Name</td><td><?php echo $name; ?></td></tr>
	    <tr><td>Short Name</td><td><?php echo $short_name; ?></td></tr>
	    <tr><td>Description</td><td><?php echo $description; ?></td></tr>
	    <tr><td>Outlet Id</td><td><?php echo $outlet_id; ?></td></tr>
	    <tr><td>Status</td><td><?php echo $status; ?></td></tr>
	    <tr><td>Menu Class Id</td><td><?php echo $menu_class_id; ?></td></tr>
	    <tr><td>Menu Group Id</td><td><?php echo $menu_group_id; ?></td></tr>
	    <tr><td>Menu Price</td><td><?php echo $menu_price; ?></td></tr>
	    <tr><td>Unit Cost</td><td><?php echo $unit_cost; ?></td></tr>
	    <tr><td>Product Id</td><td><?php echo $product_id; ?></td></tr>
	    <tr><td>Recipe Id</td><td><?php echo $recipe_id; ?></td></tr>
	    <tr><td>Recipe Qty</td><td><?php echo $recipe_qty; ?></td></tr>
	    <tr><td>Is Promo Enabled</td><td><?php echo $is_promo_enabled; ?></td></tr>
	    <tr><td>Is Export Cost</td><td><?php echo $is_export_cost; ?></td></tr>
	    <tr><td>Is Print After Total</td><td><?php echo $is_print_after_total; ?></td></tr>
	    <tr><td>Is Disable Change Price</td><td><?php echo $is_disable_change_price; ?></td></tr>
	    <tr><td>Print Kitchen Id</td><td><?php echo $print_kitchen_id; ?></td></tr>
	    <tr><td>Print Kitchen Section Id</td><td><?php echo $print_kitchen_section_id; ?></td></tr>
	    <tr><td>Created Date</td><td><?php echo $created_date; ?></td></tr>
	    <tr><td>Modified Date</td><td><?php echo $modified_date; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Modified By</td><td><?php echo $modified_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('inv_outlet_menus') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>