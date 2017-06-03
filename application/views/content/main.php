<script type="text/javascript">
	/*$(window).on('load',function(){
        $('#myModal').modal('show');
    });*/
	function load_guest(table, guest) {
		document.getElementById('table').value = table;
		if (guest == "") {
			document.getElementById('test').value = '';
		} else {
			document.getElementById('test').value = guest;
		}

		$('#myModalguest').modal('show');
		// document.getElementById("guest").focus();
		$('#test').keyboard({layout: 'qwerty', usePreview: false});
	}

</script>

<div class="modal fade" id="myModalguest" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Guest</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form" action="<?= base_url() ?>main/input_guest"
					  method="post">

					<div class="form-group">
						<label for="usr">Table:</label>
						<input type="text" class="form-control" id="table" name="table">
					</div>

					<div class="form-group">
						<label for="usr">Guest:</label>
						<input type="text" class="form-control" id="test" name="guest" required="">
					</div>
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

	<!-- Small boxes (Stat box) -->
	<div class="row">
		<?php
			$query = $this->db->query("
				select a.*, b.id `is`, b.num_of_cover guest
				from mst_pos_tables a 
				left join pos_orders b 
				on a.id=b.table_id and b.status in (0,1)
				where a.outlet_id=". $this->session->userdata('outlet') ."
				order by a.id
			");
			//echo $this->db->last_query();exit;
			foreach ($query->result() as $row) {
				?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->

					<?php
						//  if($this->global_model->get_table_available($row->id,$this->session->userdata('outlet'))==1){
						if (!($row->is)) {
							?>
							<a data-toggle="modal" href="#"
							   onclick="load_guest(<?= $row->table_no ?>, <?= $row->guest ?>)"> <img
									src="<?= base_url() ?>menu/table_avai.jpeg" width="100"
									height="100">
								<?php echo $row->table_no ?></a>

							<?php
						} else {
							?>
							<a href="<?php echo base_url() ?>main/payment/<?= $row->id ?>"> <img
									src="<?= base_url() ?>menu/table.jpeg" width="100" height="100">
								<?php echo $row->table_no ?></a>
							<?php
						}
					?>

				</div><!-- ./col -->

				<?php
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
