<div class="modal fade" id="myModalshift" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Input Shift Time</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form" action="<?php echo base_url() ?>main/save_meal_time"
					  method="post">

					<div class="form-group">
						<label>Date : </label>
						<input type="text" name="date" value="<?php echo date('Y-m-d') ?>"
							   disabled="true">
					</div>

					<div class="form-group">
						<label>Username : </label>
						<input type="text" name="username"
							   value="<?php echo $this->session->userdata('name') ?>"
							   disabled="true">
					</div>

					<div class="form-group">
						<label>Outlet : </label>
						<input type="text" name="outlet" id="outlet"
							   value="<?php echo $this->session->userdata('outlet') ?>">
					</div>

					<div class="form-group">
						<label>Batch No : </label>
						<input type="text" name="batch_no" id="batch_no" value="">
					</div>

					<div class="form-group">
						<label>House Bank : </label>
						<input type="text" name="house_bank" id="house_bank" value="">
					</div>

					<div class="form-group">
						<label>Payment Cash : </label>
						<input type="text" name="payment_cash" id="payment_cash" value="">
					</div>

					<!--
                        <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" rows="5" id="note" name="note"></textarea>
                        </div>
                    -->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- header logo: style can be found in header.less -->
<header class="header">
	<a href="#" class="logo">
		<!-- Add the class icon to your logo image or logo icon to add the margining -->
		PointOfSales
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<div class="navbar-right">
			<ul class="nav navbar-nav">

				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="glyphicon glyphicon-user"></i>
						<span><?php echo $this->session->userdata('name') ?><i
								class="caret"></i></span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header bg-light-blue">
							<img src="<?php echo MY_ASSETS ?>logo/medialogo.png" class="img-circle"
								 alt="User Image"/>
							<p>
								<?php echo $this->session->userdata('name') ?>
								<!--<small>Member since Nov. 2012</small>-->
							</p>
						</li>
						<!-- Menu Body -->

						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-right">
								<a href="<?php echo base_url('login/logout') ?>"
								   class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
