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
        <h2 style="margin-top:0px">Mst_cuisine_recipe Read</h2>
        <table class="table">
	    <tr><td>Code</td><td><?php echo $code; ?></td></tr>
	    <tr><td>Name</td><td><?php echo $name; ?></td></tr>
	    <tr><td>Short Name</td><td><?php echo $short_name; ?></td></tr>
	    <tr><td>Description</td><td><?php echo $description; ?></td></tr>
	    <tr><td>Cooking Direction</td><td><?php echo $cooking_direction; ?></td></tr>
	    <tr><td>Picture Link</td><td><?php echo $picture_link; ?></td></tr>
	    <tr><td>Status</td><td><?php echo $status; ?></td></tr>
	    <tr><td>Cost Center Id</td><td><?php echo $cost_center_id; ?></td></tr>
	    <tr><td>Meal Time Id</td><td><?php echo $meal_time_id; ?></td></tr>
	    <tr><td>Reg Style Id</td><td><?php echo $reg_style_id; ?></td></tr>
	    <tr><td>Category Id</td><td><?php echo $category_id; ?></td></tr>
	    <tr><td>Producing Qty</td><td><?php echo $producing_qty; ?></td></tr>
	    <tr><td>Unit Type Id</td><td><?php echo $unit_type_id; ?></td></tr>
	    <tr><td>Level</td><td><?php echo $level; ?></td></tr>
	    <tr><td>Product Cost</td><td><?php echo $product_cost; ?></td></tr>
	    <tr><td>Product Price</td><td><?php echo $product_price; ?></td></tr>
	    <tr><td>Makeup Cost Percent</td><td><?php echo $makeup_cost_percent; ?></td></tr>
	    <tr><td>Expected Cost Percent</td><td><?php echo $expected_cost_percent; ?></td></tr>
	    <tr><td>Selling Price</td><td><?php echo $selling_price; ?></td></tr>
	    <tr><td>Last Unit Cost</td><td><?php echo $last_unit_cost; ?></td></tr>
	    <tr><td>Last Cost Percent</td><td><?php echo $last_cost_percent; ?></td></tr>
	    <tr><td>Last Suggestion Price</td><td><?php echo $last_suggestion_price; ?></td></tr>
	    <tr><td>Onhand Unit Cost</td><td><?php echo $onhand_unit_cost; ?></td></tr>
	    <tr><td>Onhand Cost Percent</td><td><?php echo $onhand_cost_percent; ?></td></tr>
	    <tr><td>Onhand Suggestion Price</td><td><?php echo $onhand_suggestion_price; ?></td></tr>
	    <tr><td>Manual Unit Cost</td><td><?php echo $manual_unit_cost; ?></td></tr>
	    <tr><td>Manual Cost Percent</td><td><?php echo $manual_cost_percent; ?></td></tr>
	    <tr><td>Manual Suggestion Price</td><td><?php echo $manual_suggestion_price; ?></td></tr>
	    <tr><td>Created Date</td><td><?php echo $created_date; ?></td></tr>
	    <tr><td>Modified Date</td><td><?php echo $modified_date; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Modified By</td><td><?php echo $modified_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('mst_cuisine_recipe') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>