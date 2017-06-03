<script type="text/javascript">
	/*$(window).on('load',function(){
        $('#myModal').modal('show');
    });*/
	function load_guest(table) {
		document.getElementById('table').value = table;

		$('#myModalguest').modal('show');
		document.getElementById("guest").focus();
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
						<input type="text" class="form-control" id="guest" name="guest">
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
			$query = $this->db->query("select a.* from mst_pos_tables a   where a.outlet_id=" . $this->session->userdata('outlet') . "");
			foreach ($query->result() as $row) {
				?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<!--<div class="small-box <?php echo $this->global_model->get_table_available($row->id,
																								  $this->session->userdata('outlet')) == 1 ? 'bg-aqua' : 'bg-red' ?>">
                              <div class="inner">
                                    <h3>
                                        &nbsp;
                                    </h3>
                                    <p>
                                       Table No:  <?//=$row->table_no
					?><br/>
                                       Capacity : <?//=$row->cover
					?></br>
                                       Available : <?//=$this->global_model->get_table_available($row->id,$this->session->userdata('outlet'))
					?>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cutlery"></i>
                                </div>-->
					<?php
						if ($this->global_model->get_table_available($row->id,
																	 $this->session->userdata('outlet')) == 1
						) {
							?>
							<!-- <a href="<?= base_url() ?>main/order/<?= $row->id ?>"> <img src="<?= base_url() ?>menu/table_avai.jpeg" width="100" height="100">
                                    <?php echo $row->table_no ?></a>-->

							<!--<a   data-toggle="modal" href="#myModalguest"> <img src="<?= base_url() ?>menu/table_avai.jpeg" width="100" height="100">
                                    <?php echo $row->table_no ?></a>-->

							<a data-toggle="modal" href="#"
							   onclick="load_guest(<?= $row->table_no ?>)">
								<img src="<?= base_url() ?>menu/table_avai.jpeg" width="100"
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
