<?php $shift = $this->session->userdata('shift'); ?>
<div class="modal fade" id="myModalShift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Shift Revenue</h4>
			</div>
			<div class="modal-body">
				?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalMeal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Close Meal Time</h4>
			</div>
			<div class="modal-body">
				?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-lg-6">
			<label>Outlet :</label>
			<span><?php echo $shift->outlet_name;?></span>
		</div>
		<div class="col-lg-6">
			<label>Cashier Name :</label>
			<span><?php echo $this->session->userdata('full_name');?></span>
		</div>
		<div class="col-lg-6">
			<label>Shift :</label>
			<span><?php echo $shift->shift_name.' ('.$shift->should_start_time.' ~ '.$shift->should_end_time.')';?></span>
		</div>
		<div class="col-lg-6">
			<label>Date :</label>
			<span><?php echo date('Y-m-d H:i:s');?></span>
		</div>
		<div class="col-lg-6">
			<label>Time In :</label>
			<span><?php echo $shift->start_time;?></span>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-lg-12">
			<a class="btn btn-app" style="margin: 0px 10px 10px 0px;" data-toggle="modal" href="#myModalShift">
				<i class="fa fa-money"></i>
				Close shift
			</a>
			<a class="btn btn-app" style="margin: 0px 10px 10px 0px;"  data-toggle="modal" href="#myModalMeal">
				<i class="fa fa-clock-o"></i>
				Close meal time
			</a>
		</div>
	</div>
</section>
