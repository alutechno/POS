<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= MY_ASSETS ?>logo/medialogo.png" class="img-circle" alt="User Image"/>
			</div>
			<div class="pull-left info">
				<p>The Media</p>
				<a href="#"></i> Hotel and Tower</a>
			</div>
		</div>
		<!-- search form -->
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">&nbsp;
				<!--<input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>-->
			</div>
		</form>
		<!-- /.search form  <? echo $this->uri->segment(1) == 'referensi' ? 'active' : ''; ?>-->
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li>
				<a href="<?= base_url('cashier') ?>">
					<i class="fa fa-clock-o"></i>
					<span>Cashier</span>
				</a>
			</li>
			<li>
				<a href="<?= base_url('main') ?>">
					<i class="fa fa-money"></i>
					<span>Point Of Sales</span>
				</a>
			</li>
			<!--<li class="<? /* echo $this->uri->segment(1) == 'pos' ? 'active' : ''; */ ?>">
				<a href="<? /*= base_url('pos/cashier') */ ?>">
					<i class="fa fa-money"></i> <span>Cashier</span>
				</a>
			</li>-->
			<li class="treeview <?php echo $this->uri->segment(1) == 'referensi' ? 'active' : ''; ?>">
				<a href="#">
					<i class="fa fa-table"></i> <span>Referensi</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="<?php echo $this->uri->segment(2) == 'ref_outlet_type' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_outlet_type') ?>"><i
								class="fa fa-angle-double-right"></i> Outlet Type</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_payment_method' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_payment_method') ?>"><i
								class="fa fa-angle-double-right"></i> Payment Method</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_pos_segment' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_pos_segment') ?>"><i
								class="fa fa-angle-double-right"></i> Customer Segment</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_pos_segment_payment' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_pos_segment_payment') ?>"><i
								class="fa fa-angle-double-right"></i>Segment Payment</a></li>
					<!--<li class="<? echo $this->uri->segment(2) == 'ref_pos_payment_method' ? 'active' : ''; ?>"><a href="<?php echo base_url('referensi/ref_pos_segment_payment') ?>"><i class="fa fa-angle-double-right"></i> Payment Segment</a></li>   -->
					<li class="<?php echo $this->uri->segment(2) == 'ref_outlet_menu_class' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_outlet_menu_class') ?>"><i
								class="fa fa-angle-double-right"></i> Category Class</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_outlet_menu_group' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_outlet_menu_group') ?>"><i
								class="fa fa-angle-double-right"></i>Group of Menu</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_cuisine_category' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_cuisine_category') ?>"><i
								class="fa fa-angle-double-right"></i>Cuisine Category</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_cuisine_region' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_cuisine_region') ?>"><i
								class="fa fa-angle-double-right"></i>Cuisine Region</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'ref_meal_time' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/ref_meal_time') ?>"><i
								class="fa fa-angle-double-right"></i>Meal Time</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'inv_outlet_menus' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/inv_outlet_menus') ?>"><i
								class="fa fa-angle-double-right"></i>Menu List</a></li>
					<!--<li class="<? echo $this->uri->segment(2) == 'ref_pos_payment_method' ? 'active' : ''; ?>"><a href="<? //=base_url('referensi/pos_menus_tax')?>"><i class="fa fa-angle-double-right"></i>Menu Tax</a></li> -->
					<li class="<?php echo $this->uri->segment(2) == 'pos_menus_promos' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('referensi/pos_menus_promos') ?>"><i
								class="fa fa-angle-double-right"></i>Promotion</a></li>
				</ul>
			</li>

			<li class="treeview <?php echo $this->uri->segment(1) == 'master' ? 'active' : ''; ?>">
				<a href="#">
					<i class="fa fa-table"></i> <span>Master</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="<?php echo $this->uri->segment(2) == 'mst_outlet' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_outlet') ?>"><i
								class="fa fa-angle-double-right"></i> Outlet</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_pos_tables' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_pos_tables') ?>"><i
								class="fa fa-angle-double-right"></i> Tables</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_pos_taxes' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_pos_taxes') ?>"><i
								class="fa fa-angle-double-right"></i> Tax</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_house_use' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_house_use') ?>"><i
								class="fa fa-angle-double-right"></i> House Use</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_pos_discount' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_pos_discount') ?>"><i
								class="fa fa-angle-double-right"></i> Discount</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_pos_cost_center' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_pos_cost_center') ?>"><i
								class="fa fa-angle-double-right"></i> Cost Center</a></li>
					<!--<li class="<?/* echo $this->uri->segment(2) == 'mst_pos_type' ? 'active' : ''; */?>"><a
							href="<?php /*echo base_url('master/mst_pos_type') */?>"><i class="fa fa-angle-double-right"></i> POS Type</a></li>-->
					<li class="<?php echo $this->uri->segment(2) == 'mst_kitchen' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_kitchen') ?>"><i
								class="fa fa-angle-double-right"></i>Kitchen</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_kitchen_section' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_kitchen_section') ?>"><i
								class="fa fa-angle-double-right"></i>Kitchen Section</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_cuisine_recipe' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_cuisine_recipe') ?>"><i
								class="fa fa-angle-double-right"></i>Cuisine Recipe</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'mst_pos_member_package' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('master/mst_pos_member_package') ?>"><i
								class="fa fa-angle-double-right"></i> Member Package</a></li>
					<!--<li class="<?/* echo $this->uri->segment(2) == 'mst_pos_scheduled_discount' ? 'active' : ''; */?>"><a
							href="<?/*= base_url('master/mst_pos_scheduled_discount') */?>"><i class="fa fa-angle-double-right"></i>scheduled discount</a>
					</li>-->
				</ul>
			</li>

			<li class="<?php echo $this->uri->segment(1) == 'report' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('report/report_list') ?>">
					<i class="fa fa-dashboard"></i> <span>Report</span>
				</a>
			</li>

			<li class="treeview <?php echo $this->uri->segment(1) == 'setting' ? 'active' : ''; ?>">
				<a href="#">
					<i class="fa fa-cog"></i> <span>Setting</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="<?php echo $this->uri->segment(2) == 'mst_outlet' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('setting/mst_outlet') ?>"><i
								class="fa fa-angle-double-right"></i> Outlet</a></li>
					<!--<li class="<?/* echo $this->uri->segment(2) == 'pos_outlet_zones' ? 'active' : ''; */?>"><a
							href="<?php /*echo base_url('setting/pos_outlet_zones') */?>"><i class="fa fa-angle-double-right"></i> Outlet Zone</a></li>-->
					<li class="<?php echo $this->uri->segment(2) == 'pos_outlet_tables' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('setting/pos_outlet_tables') ?>"><i
								class="fa fa-angle-double-right"></i> Tables Outlet</a></li>
					<li class="<?php echo $this->uri->segment(2) == 'user' ? 'active' : ''; ?>"><a
							href="<?php echo base_url('setting/user') ?>"><i
								class="fa fa-angle-double-right"></i> Employee Staff</a></li>

				</ul>
			</li>

		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
