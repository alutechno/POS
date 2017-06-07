<select class="form-control">
	<option>- Choose -</option>
	<?php
		$dddd = $this->db->query("select id, code, name, description from ref_payment_method where category = 'CC' and status = '1'");
		foreach ($dddd->result() as $row) {
			?>
			<script>
				window.CardMethod = window.CardMethod || {};
				CardMethod[<?php echo $row->id ?>] = <?php echo json_encode($row) ?>;
			</script>
			<option value="<?php echo $row->id ?>">
				<?php echo $row->code . ' - ' . $row->name ?>
			</option>
			<?php
		}
	?>
</select>
<select class="form-control" name="charge_to_room"
		id="charge_to_room">
	<option>- Choose -</option>
	<?php
		$dddd = $this->db->query("select * from v_in_house_guest");
		foreach ($dddd->result() as $row) {
			?>
			<script>
				window.Charge2Room = window.Charge2Room || {};
				Charge2Room[<?php echo $row->folio_id ?>] =
				<?php echo json_encode($row) ?>;
			</script>
			<option value="<?php echo $row->folio_id ?>">
				[<?php echo $row->room_type . ' / ' . $row->room_no ?>
				]
				&nbsp;
				<?php echo $row->cust_firt_name . ' ' . $row->cust_last_name
				?>
			</option>
			<?php
		}
	?>
</select>
<select class="form-control" name="guest_use" id="guest_use">
	<option>- Choose -</option>
	<?php
		$dddd = $this->db->query(
			"select
												a.*, b.current_transc_amount, b.house_use,
												a.id house_use_id, b.period, b.cost_center,
												c.name pos_cost_center_name
											from mst_house_use a
											left join v_house_use_spent_monthly b on a.id = b.house_use_id and b.period = DATE_FORMAT(NOW(), '%Y%m')
											left join mst_pos_cost_center c on c.id = a.pos_cost_center_id
											where a.status = 1"
		);
		foreach ($dddd->result() as $row) {
			?>
			<script>
				window.GuestUse = window.GuestUse || {};
				GuestUse[<?php echo $row->house_use_id ?>] =
				<?php echo json_encode($row) ?>;
			</script>
			<option value="<?php echo $row->house_use_id ?>">
				[<?php echo $row->code ?>]
				&nbsp;
				<?php echo $row->pos_cost_center_name . ' / ' .
					$row->name ?>
			</option>
			<?php
		}
	?>
</select>
<div class="modal fade" id="myModalSplit" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Split Billing</h4>
			</div>
			<div class="modal-body" style="padding-bottom: 0px;">
				<?php
					$orderId = $this->uri->segment(3);
					$orderId = explode('-', $orderId);
					$orderId = implode(",", $orderId);
					$q = $this->db->query("
							select
							c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
							from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
							sum(sub_total_amount) total
							from pos_orders
							where id in(" . $orderId . ")) d
							where b.tax_id=c.id
							and b.order_id in(" . $orderId . ")
							group by c.name;"
					);
					$rows = $q->result();
					function html($label, $val, $id = '') {
						$val = floatval($val);
						if (!($val > 0)) $val = 0;

						return '
								<div class="row">
									<div class="col-sm-6">
										<label>' . $label . '</label>
									</div>
									<div class="col-sm-6 text-right">
										<label style="margin-right: 13px;" for="' . $id . '" val="' . $val . '">
										' . rupiah($val, 2) . '</label>
									</div>
								</div>';
					}

					echo html('Total', $rows[0]->total);
					foreach ($rows as $row) {
						$l = '&nbsp&nbsp' . $row->name . '<small>&nbsp&nbsp&nbsp' .
							rupiah($row->tax_percent, 2) .
							'% </small>';
						$v = $row->tax_amount;
						echo html($l, $v);
					}
					echo html('Grand Total', $rows[0]->grandtotal, 'grandtot');
					echo html('Balance', $rows[0]->grandtotal, 'balance');
				?>
				<hr/>
				<div class="row" id="noState">
					<div class="col-sm-12">
						<div class="form-group row" style="margin-bottom:0px;">
							<h5 for="inputKey" class="col-sm-2 control-label">
								<label>Split with</label>
							</h5>
							<div class="col-sm-4">
								<select class="form-control" id="mode">
									<option value="">- Choose one -</option>
									<option value="manual">Manually</option>
									<!--
									<option value="event">Evently</option>
                                    <option value="item">Items</option>
                                    -->
								</select>
							</div>
							<div class="col-sm-4">
								<input type="number" class="form-control" id="modeCounter"/>
							</div>
						</div>
					</div>
				</div>
				<div id="state" style="display:none;">
					<div class="row">
						<div class="col-sm-12">
							<h5>
								<label id="modeLabel0"></label>
								<label style="margin: 0 10px;">/</label>
								<label id="modeLabel1"></label>
							</h5>
						</div>
					</div>
					<div class="row payment-state" id="manualState" style="max-height: 300px;overflow-y: auto;display: none;"></div>
					<div class="row payment-state" id="eventState" style="max-height: 300px;overflow-y: auto;display: none;"></div>
					<div class="row payment-state" id="itemState" style="max-height: 300px;overflow-y: auto;display: none;"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="next" class="btn btn-primary" disabled>Next</button>
			</div>
		</div>
	</div>
</div>
<script>
	var delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();
	var trim = function (str) {
		return str.replace(/\t\t+|\n\n+|\s\s+/g, ' ').trim()
	};
	var swipeCard = function (str) {
		return trim(str)
		.replace(/\/\s\^|\s\^/g, '/^')
		.split(/\;|\%B|\^|\/\^|\?\;|\=|\?/g)
		.slice(1, -1)
	};
	var rupiahJS = function (val) {
		return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
		.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	};
	$(function () {
		var m = $('#myModalSplit');
		var close = m.find('#close');
		var next = m.find('#next');
		var mode = m.find('#mode');
		var modeCounter = m.find('#modeCounter');
		var noState = m.find('#noState');
		var state = m.find('#state');
		var payments = m.find('.payment-state');
		var modeLabel0 = m.find('#modeLabel0');
		var modeLabel1 = m.find('#modeLabel1');
		var grandtototal = m.find('label[for="grandtot"]');
		var balance = m.find('label[for="balance"]');
		var grandtotal = m.find('label[for="grandtot"]');
		//
		var count = 0;
		var manualMode = m.find('#manualMode');
		var manualState = m.find('#manualState');
		var eventState = m.find('#eventState');
		var itemState = m.find('#itemState');
		var payment = function (i) {
			var pay = $(`
			<div id="split-${i}" class=col-sm-12>
				<div class="row">
					<div id="split-${i}-mode" class="col-sm-12">
						<div class="form-group row">
							<div class="col-sm-2"><h5>Pay Method</h5></div>
							<div class="col-sm-4">
								<select class="form-control"">
									<option> - Choose - </option>
									<option value="cash">Cash</option>
									<option value="card">Card</option>
									<option value="charge2room">Charge to room</option>
									<option value="houseuse">House use</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-12"><hr style="margin: 0px 0px 10px;"/></div>
					<div id="split-${i}-pay" class="col-sm-12"></div>
				</div>
			</div>
			`);
			var select = pay.find('select');
			select.on('change', function () {
				var el = $(this);
				var payDialog = pay.find(`#split-${i}-pay`);

				next.disable();
				payDialog.html('');
				if (el.val() == 'cash') {
					payDialog.append(payWithCash(`split-${i}-cash`));
				} else if (el.val() == 'card') {
					payDialog.append(payWithCard(`split-${i}-card`));
				} else if (el.val() == 'charge2room') {
					payDialog.append(payWithCharge2Room(`split-${i}-charge`));
				} else if (el.val() == 'houseuse') {
					payDialog.append(payWithHouseUse(`split-${i}-house`));
				}
			});
			return pay;
		};
		var payWithCash = function (id) {
			var pay = $(`<div class="row">
				<div class="col-sm-6">
					<label for="usr">Amount</label>
				</div>
				<div class="col-sm-6 text-right">
					<input id="${id}-amount" type="currency" class="form-control">
				</div>
			</div>`);
			var amount = pay.find(`#${id}-amount`);
			amount.keyboard({layout: 'num'});
			amount.css('text-align', 'end');
			amount.on('change', function () {
				var el = $(this);
				var val = el.val();
				el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
				el.data('display',
					parseFloat(val.replace(/\,/g, ""))
					.toFixed(2).toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				);
				el.attr('value', el.data('display'));
				el.val(el.data('display'));
				next.disable();
				if (parseFloat(el.data('value')) > 0) {
					next.enable();
				}
			});
			return pay;
		}
		var payWithCard = function (id) {
		    var options = '';
			for (var c in window.CardMethod) {
			    var el = window.CardMethod[c];
				options += `<option value="${c}">${el.code} - ${el.name}</option>`
			}
			var pay = $(`
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label>Type</label>
							<select class="form-control" id="${id}-select">
								<option value="credit">Credit</option>
								<option value="debit">Debit</option>
							</select>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="form-group">
							<label>Bank</label>
							<select class="form-control">
								<option>- Choose -</option>
								${options}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<input id="${id}-swiper" type="force-text" class="form-control"
							   placeholder="Tap here, then swipe the card">
						<br/>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<div class="form-group">
							<label for="usr">Number:</label>
							<input id="${id}-cardno" type="text" class="form-control">
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-group">
							<label for="usr">Name:</label>
							<input id="${id}-customer" type="text" class="form-control">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label for="usr">Amount</label>
					</div>
					<div class="col-sm-6 text-right">
						<input id="${id}-amount" type="currency" class="form-control">
					</div>
				</div>
			</div>
			`);
			var amount = pay.find(`#${id}-amount`);
			var select = pay.find(`#${id}-select`);
			var cardNo = pay.find(`#${id}-cardno`);
			var customer = pay.find(`#${id}-customer`);
			var swiper = pay.find(`#${id}-swiper`);
			//
			amount.keyboard({layout: 'num'});
			amount.css('text-align', 'end');
			amount.on('change', function () {
				var el = $(this);
				var val = el.val();
				el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
				el.data('display',
					parseFloat(val.replace(/\,/g, ""))
					.toFixed(2).toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				);
				el.attr('value', el.data('display'));
				el.val(el.data('display'));
				next.disable();
				if (parseFloat(el.data('value')) > 0) {
					next.enable();
				}
			});
			cardNo.keyboard({layout: 'qwerty'});
			customer.keyboard({layout: 'qwerty'});
			select.on('change', function () {
				var v = $(this).val();
				if (v == 'debit') {
					customer.parent().hide();
					customer.val('');
				} else {
					customer.parent().show();
				}
			});
			swiper.keydown(function (e) {
				if (e.keyCode == 13) {
					e.preventDefault();
				}
				return
			});
			swiper.on('keyup', function () {
				var el = $(this);
				delay(function () {
					var arr = swipeCard(el.val());
					customer.val('');
					if (arr.length) {
						cardNo.val(arr[0]);
						if (select.val() == 'credit') {
							customer.val(arr[1]);
						} else {
						}
					}
					el.val('');
				}, 1000);
			});
			cardNo.on('change', function(){
				var val = $(this).val();
				next.disable();
				if (val && customer.val()) {
				    next.enable();
				}
			});
			customer.on('change', function(){
				var val = $(this).val();
				next.disable();
				if (val && cardNo.val()) {
					next.enable();
				}
			});

			return pay;
		}
		var payWithCharge2Room = function (id) {
			var options = '';
			for (var c in window.Charge2Room) {
				var el = window.Charge2Room[c];
				options += `
				<option value="${el.folio_id}">
					[${el.room_type} / ${el.room_no}] &nbsp; ${el.cust_firt_name} ${el.cust_last_name}
				</option>
				`
			}
			var pay = $(`
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>Customer</label>
							<select class="form-control" id="${id}-select">
								<option>- Choose -</option>
								${options}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label>Check In</label>
						<span> : </span>
						<span id="${id}-check_in_date"></span>
					</div>
					<div class="col-sm-6">
						<label>Departure</label>
						<span> : </span>
						<span id="${id}-departure_date"></span>
					</div>
					<div class="col-sm-6">
						<label>Cash Bases</label>
						<span> : </span>
						<span id="${id}-is_cash_bases"></span>
					</div>
					<div class="col-sm-6">
						<label>Room Only</label>
						<span> : </span>
						<span id="${id}-is_room_only"></span>
					</div>
					<div class="col-sm-6">
						<label>Reservation Type</label>
						<span> : </span>
						<span id="${id}-reservation_type"></span>
					</div>
					<div class="col-sm-6">
						<label>Room No</label>
						<span> : </span>
						<span id="${id}-room_no"></span>
					</div>
					<div class="col-sm-6">
						<label>Room Rate</label>
						<span> : </span>
						<span id="${id}-room_rate_code"></span>
					</div>
					<div class="col-sm-6">
						<label>Room Type</label>
						<span> : </span>
						<span id="${id}-room_type"></span>
					</div>
					<div class="col-sm-6">
						<label>VIP Type</label>
						<span> : </span>
						<span id="${id}-vip_type"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label for="usr">Amount</label>
					</div>
					<div class="col-sm-6 text-right">
						<input id="${id}-amount" type="currency" class="form-control">
					</div>
				</div>
			`);
			var amount = pay.find(`#${id}-amount`);
			var select = pay.find(`#${id}-select`);
			//
			amount.keyboard({layout: 'num'});
			amount.css('text-align', 'end');
			amount.on('change', function () {
				var el = $(this);
				var val = el.val();
				el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
				el.data('display',
					parseFloat(val.replace(/\,/g, ""))
					.toFixed(2).toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				);
				el.attr('value', el.data('display'));
				el.val(el.data('display'));
				next.disable();
				if (parseFloat(el.data('value')) > 0) {
					next.enable();
				}
			});
			select.on('change', function () {
				var i = $(this).val();
				var d = Charge2Room.current = Charge2Room[i];
				if (d) {
					pay.find(`#${id}-check_in_date`).html(d.check_in_date);
					pay.find(`#${id}-departure_date`).html(d.departure_date);
					pay.find(`#${id}-is_cash_bases`).html(d.is_cash_bases);
					pay.find(`#${id}-is_room_only`).html(d.is_room_only);
					pay.find(`#${id}-reservation_type`).html(d.reservation_type);
					pay.find(`#${id}-room_no`).html(d.room_no);
					pay.find(`#${id}-room_rate_code`).html(d.room_rate_code + '/' + d.room_rate_name);
					pay.find(`#${id}-room_type`).html(d.room_type + '/' + d.room_type_name);
					pay.find(`#${id}-vip_type`).html(d.vip_type);
					if (d.is_cash_bases.toLowerCase() === 'n') {
						next.enable();
					}
				}
			});
			return pay;
		}
		var payWithHouseUse = function (id) {
		    var options = ''
			for (var c in window.GuestUse) {
				var el = window.GuestUse[c];
				options += `<option value="${el.house_use_id}">[${el.code}] &nbsp; ${el.pos_cost_center_name}  / ${el.name}</option>`
			}
			var pay = $(`
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>House use</label>
							<select class="form-control" id="${id}-select">
								<option>- Choose -</option>
								${options}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<label>Period</label>
						<span> : </span>
						<span id="${id}-period"></span>
					</div>
					<div class="col-sm-12">
						<label>House use</label>
						<span> : </span>
						<span id="${id}-house_use"></span>
					</div>
					<div class="col-sm-12">
						<label>Cost Center</label>
						<span> : </span>
						<span id="${id}-cost_center"></span>
					</div>
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6">
								<label>Monthly Spent</label>
							</div>
							<div class="col-sm-6 pull-right">
								<span id="${id}-max_spent_monthly" class="pull-right"></span>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6">
								<label>Current Transaction Amount</label>
							</div>
							<div class="col-sm-6 pull-right">
								<span id="${id}-current_transc_amount" class="pull-right"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label for="usr">Amount</label>
					</div>
					<div class="col-sm-6 text-right">
						<input id="${id}-amount" type="currency" class="form-control">
					</div>
				</div>
			`);
		    //
			var spent = 0, should = false;
			var amount = pay.find(`#${id}-amount`);
			var select = pay.find(`#${id}-select`);

			select.on('change', function () {
				var i = $(this).val();
				var d = GuestUse.current = GuestUse[i];
				d.current_transc_amount = d.current_transc_amount || 0;
				d.max_spent_monthly = d.max_spent_monthly || 0;
				should = false;
				if (d) {
					pay.find(`#${id}-cost_center`).html(d.cost_center);
					pay.find(`#${id}-house_use`).html(d.house_use);
					pay.find(`#${id}-current_transc_amount`).html(rupiahJS(d.current_transc_amount));
					pay.find(`#${id}-max_spent_monthly`).html(rupiahJS(d.max_spent_monthly));
					pay.find(`#${id}-period`).html(d.period);
					spent = parseFloat(d.max_spent_monthly) - parseFloat(d.current_transc_amount);
					if (spent > 0) {
						if (d.period && (spent - parseFloat(grandtotal.val()) >= 0)) {
							should = true;
						}
					}
				}
			});
			amount.keyboard({layout: 'num'});
			amount.css('text-align', 'end');
			amount.on('change', function () {
				var el = $(this);
				var val = el.val();
				el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
				el.data('display',
					parseFloat(val.replace(/\,/g, ""))
					.toFixed(2).toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				);
				el.attr('value', el.data('display'));
				el.val(el.data('display'));
				next.disable();
				if (should && spent && parseFloat(el.data('value')) > 0) {
					next.enable();
				}
			});
			return pay;
		}
		//
		grandtotal.val = getLabelValue;
		balance.val = getLabelValue;
		close.enable = function () {
			close.removeAttr('disabled');
		};
		close.disable = function () {
			close.attr('disabled', 1);
		};
		next.enable = function () {
			next.removeAttr('disabled');
		};
		next.disable = function () {
			next.attr('disabled', 1);
		};
		next.on('click', function () {
			noState.hide();
			state.show();
			next.disable();
			var activePayment = payments.filter(function(i, e){
				return ($(this).is(':visible'))
			});
			if (activePayment.length) {
			    var bayar = parseFloat(activePayment.find('[id*="-amount"]').data('value'));
				var nextBalance = balance.val() - parseFloat(bayar);
				if (nextBalance > 0) {
					next.disable();
					balance.val(nextBalance);
					balance.html(rupiahJS(nextBalance));
					console.log('AJAX SAVING.. PRINTING..');
					setTimeout(function () {
						activePayment.hide();
						next.click();
					}, 2000)
				} else if (nextBalance <= 0) {
					next.disable();
					balance.val(nextBalance);
					balance.html(rupiahJS(nextBalance));
					console.log('AJAX SAVING.. PRINTING.. END..');
					setTimeout(function () {
						activePayment.hide();
						close.enable();
						state.hide();
						noState.hide();
					}, 2000)
				}
			} else {
				itemState.hide();
				eventState.hide();
				manualState.hide();
				count += 1;
				if (mode.val() == 'manual') {
					modeLabel0.html('Manual');
					modeLabel1.html(count);
					modeCounter.show();
					manualState.show();
					manualState.html('');
					manualState.append(payment(count));
				}
			}
		});
		mode.on('change', function () {
			var v = $(this).val();
			if (v) {
				next.enable();
				close.disable();
			}
		})
		//
		m.on('shown', function() {
			modeCounter.hide();
			noState.show();
			state.show();
			count = 0;
		})
		modeCounter
		m.modal('show');
		function getLabelValue (value) {
			if (value) $(this).attr('val', value);
			var val = $(this).attr('val');

			return parseFloat(val);
		};
	})
</script>
