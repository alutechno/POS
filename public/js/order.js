let delay = (function () {
    let timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();
let trim = function (str) {
    return str.replace(/\t\t+|\n\n+|\s\s+/g, ' ').trim()
};
let swipeCard = function (str) {
    return trim(str)
    .replace(/\/\s\^|\s\^/g, '/^')
    .split(/\;|\%B|\^|\/\^|\?\;|\=|\?/g)
    .slice(1, -1)
};
let rupiahJS = function (val) {
    return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
    .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
};
let getLabelValue = function (value) {
    if (value) $(this).attr('val', value);
    let val = $(this).attr('val');

    return parseFloat(val);
};
let recordPayment = function (i, value) {
    return $(`
        <div class="row">
            <div class="col-sm-6">
                <label style="margin-left: 10px;">Payment #${i}</label>
            </div>
            <div class="col-sm-6 text-right">
                <label style="margin-right: 13px;">
                    ${rupiahJS(value)}
                </label>
            </div>
        </div>
    `);
};
let print = function (order) {
    PopupCenter('http://localhost:8080/birt/frameset?__report=report/pos/struk_kitchen.rptdesign&__format=pdf&no_bill=&table=95&date=2017-Jun-17&outlet=1&waitress=test', 'xtf', '600', '600');
    myWindow.document.close();
    myWindow.focus();
    myWindow.print();
    myWindow.close();
};
let PopupCenter = function (url, title, w, h) {
    let dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    let dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    let width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    let height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    let left = ((width / 2) - (w / 2)) + dualScreenLeft;
    let top = ((height / 2) - (h / 2)) + dualScreenTop;
    let newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
};
let cancel = function () {
    alert('Cancel This Order?');
};
let load = function () {
    $("#select_sub_menu").val("");
    document.forms["myform"].submit();
};
let load_sub = function () {
    document.forms["myform"].submit();
};
let splitBillsInit = function () {
    let m = $('#myModalSplit');
    let close = m.find('#close');
    let next = m.find('#next');
    let mode = m.find('#mode');
    let modeCounter = m.find('#modeCounter');
    let noState = m.find('#noState');
    let state = m.find('#state');
    let payments = m.find('.payment-state');
    let modeLabel0 = m.find('#modeLabel0');
    let modeLabel1 = m.find('#modeLabel1');
    let grandtototal = m.find('label[for="grandtot"]');
    let balance = m.find('label[for="balance"]');
    let grandtotal = m.find('label[for="grandtot"]');
    let recordList = $('<div>');
    //
    let count = 0;
    let manualMode = m.find('#manualMode');
    let manualState = m.find('#manualState');
    let eventState = m.find('#eventState');
    let itemState = m.find('#itemState');
    let sendData = function (data, cb) {
        $.ajax({
            method: 'post',
            url: '/main/submit_split',
            data, complete: cb
        });
    }
    let payment = function (i) {
        let pay = $(`
			<div id="split-${i}" class=col-sm-12>
				<div class="row">
					<div id="split-${i}-mode" class="col-sm-12">
						<div class="form-group row">
							<div class="col-sm-2"><h5>Method</h5></div>
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
        let select = pay.find('select');
        select.on('change', function () {
            let el = $(this);
            let payDialog = pay.find(`#split-${i}-pay`);

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
    let payWithCash = function (id) {
        let limit = parseInt(modeCounter.val());
        let pay = $(`
            <div class="row">
                <div class="col-sm-6">
                    <label for="usr">Pay with</label>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-paywith" type="currency" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="usr">Pay Amount</label>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Change</label>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-change" class="pull-right" style="margin-right: 13px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        `);
        let change = pay.find(`#${id}-change`);
        let paywith = pay.find(`#${id}-paywith`);
        let amount = pay.find(`#${id}-amount`);
        paywith.keyboard({layout: 'num'});
        paywith.css('text-align', 'end');
        paywith.on('change', function () {
            let el = $(this);
            let val = el.val();
            el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
            el.data('display',
                parseFloat(val.replace(/\,/g, ""))
                .toFixed(2).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            );
            el.attr('value', el.data('display'));
            el.val(el.data('display'));
            next.disable();

            let a = parseFloat(el.data('value'));
            let b = parseFloat(amount.data('value'));
            if ((a > 0) && (a >= b)) {
                if (limit == count) {
                    if (a >= balance.val()) {
                        change.attr('val', a - b);
                        change.html(rupiahJS(a - b))
                        next.enable();
                    }
                } else {
                    change.attr('val', a - b);
                    change.html(rupiahJS(a - b))
                    next.enable();
                }
            }
        });
        amount.keyboard({layout: 'num'});
        amount.css('text-align', 'end');
        amount.on('change', function () {
            let el = $(this);
            let val = el.val();
            el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
            el.data('display',
                parseFloat(val.replace(/\,/g, ""))
                .toFixed(2).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            );
            el.attr('value', el.data('display'));
            el.val(el.data('display'));
            next.disable();

            let a = parseFloat(el.data('value'));
            let b = parseFloat(paywith.data('value'));
            if ((a > 0) && (b >= a)) {
                if (limit == count) {
                    if (a >= balance.val()) {
                        change.attr('val', b - a);
                        change.html(rupiahJS(b - a))
                        next.enable();
                    }
                } else {
                    change.attr('val', b - a);
                    change.html(rupiahJS(b - a))
                    next.enable();
                }
            }
        });
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    }
    let payWithCard = function (id) {
        let limit = parseInt(modeCounter.val());
        let options = '';
        for (let c in window.CardMethod) {
            if (parseInt(c)) {
                let el = window.CardMethod[c];
                options += `<option value="${c}">${el.code} - ${el.name}</option>`
            }
        }
        let pay = $(`
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
                        <select class="form-control" id="${id}-card_type">
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
                    <label for="usr">Pay Amount</label>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
        `);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        let cardType = pay.find(`#${id}-card_type`);
        let cardNo = pay.find(`#${id}-cardno`);
        let customer = pay.find(`#${id}-customer`);
        let swiper = pay.find(`#${id}-swiper`);
        let validate = function () {
            let typeVal = select.val();
            let customerVal = customer.val();
            let cardNoVal = cardNo.val();
            let cardTypeVal = parseInt(cardType.val());
            let amountVal = parseFloat(amount.data('value'));
            next.disable();
            if (typeVal == 'credit') {
                customer.parent().show();
                if (customerVal && cardNoVal && cardTypeVal && (amountVal > 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            } else {
                customer.val('');
                customer.parent().hide();
                if (cardNoVal && cardTypeVal && (amountVal > 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            }
        }
        //
        amount.keyboard({layout: 'num'});
        amount.css('text-align', 'end');
        amount.on('change', function () {
            let el = $(this);
            let val = el.val();
            el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
            el.data('display',
                parseFloat(val.replace(/\,/g, ""))
                .toFixed(2).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            );
            el.attr('value', el.data('display'));
            el.val(el.data('display'));
            //
            let typeVal = select.val();
            let customerVal = customer.val();
            let cardNoVal = cardNo.val();
            let cardTypeVal = parseInt(cardType.val());
            let amountVal = parseInt(el.data('value'));
            next.disable();
            if (typeVal == 'credit') {
                customer.parent().show();
                if (customerVal && cardNoVal && cardTypeVal && (amountVal > 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            } else {
                customer.val('');
                customer.parent().hide();
                if (cardNoVal && cardTypeVal && (amountVal > 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            }
        });
        cardNo.keyboard({layout: 'qwerty'});
        customer.keyboard({layout: 'qwerty'});
        swiper.keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
            return
        });
        swiper.on('keyup', function () {
            let el = $(this);
            delay(function () {
                let arr = swipeCard(el.val());
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
        select.on('change', validate);
        cardType.on('change', validate)
        cardNo.on('change', validate);
        customer.on('change', validate);

        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }

        return pay;
    }
    let payWithCharge2Room = function (id) {
        let limit = parseInt(modeCounter.val());
        let options = '';
        for (let c in window.Charge2Room) {
            if (parseInt(c)) {
                let el = window.Charge2Room[c];
                options += `
                    <option value="${el.folio_id}">
                        [${el.room_type} / ${el.room_no}] &nbsp; ${el.cust_firt_name} ${el.cust_last_name}
                    </option>
                `;
            }
        }
        let pay = $(`
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
                    <label for="usr">Pay Amount</label>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
        `);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        //
        amount.keyboard({layout: 'num'});
        amount.css('text-align', 'end');
        amount.on('change', function () {
            let el = $(this);
            let val = el.val();
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
                if (limit == count) {
                    if (parseFloat(el.data('value')) >= balance.val()) {
                        if (pay.find(`#${id}-is_cash_bases`).html().toLowerCase() === 'n') {
                            next.enable();
                        }
                    }
                } else if (pay.find(`#${id}-is_cash_bases`).html().toLowerCase() === 'n') {
                    next.enable();
                }
            }
        });
        select.on('change', function () {
            let i = $(this).val();
            let d = Charge2Room.current = Charge2Room[i];
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
                    if (parseFloat(amount.data('value')) > 0) {
                        if (limit == count) {
                            if (parseFloat(amount.data('value')) >= balance.val()) {
                                next.enable();
                            }
                        } else {
                            next.enable();
                        }
                    }
                }
            }
        });
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    }
    let payWithHouseUse = function (id) {
        let limit = parseInt(modeCounter.val());
        let options = ''
        for (let c in window.HouseUse) {
            if (parseInt(c)) {
                let el = window.HouseUse[c];
                options += `<option value="${el.house_use_id}">[${el.code}] &nbsp; ${el.pos_cost_center_name}  / ${el.name}</option>`
            }
        }
        let pay = $(`
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
                    <label for="usr">Pay Amount</label>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
        `);
        //
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);

        select.on('change', function () {
            let i = $(this).val();
            next.disable();
            if (parseInt(i)) {
                let d = HouseUse.current = HouseUse[i];
                d.current_transc_amount = d.current_transc_amount || 0;
                d.max_spent_monthly = d.max_spent_monthly || 0;
                if (d) {
                    pay.find(`#${id}-cost_center`).html(d.cost_center);
                    pay.find(`#${id}-house_use`).html(d.house_use);
                    pay.find(`#${id}-current_transc_amount`).html(rupiahJS(d.current_transc_amount));
                    pay.find(`#${id}-max_spent_monthly`).html(rupiahJS(d.max_spent_monthly));
                    pay.find(`#${id}-period`).html(d.period || `/* NO PERIOD! */`);
                    //
                    let spent = parseFloat(d.max_spent_monthly) - parseFloat(d.current_transc_amount);
                    let paywith = parseFloat(amount.data('value'));
                    if (d.period && paywith && (spent >= paywith)) {
                        if (limit == count) {
                            if (paywith >= parseFloat(balance.val())) {
                                next.enable();
                            }
                        } else {
                            next.enable();
                        }
                    }
                }
            }
        });
        amount.keyboard({layout: 'num'});
        amount.css('text-align', 'end');
        amount.on('change', function () {
            let el = $(this);
            let val = el.val();
            el.data('value', parseFloat(val.replace(/\,/g, "")).toFixed(2));
            el.data('display',
                parseFloat(val.replace(/\,/g, ""))
                .toFixed(2).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            );
            el.attr('value', el.data('display'));
            el.val(el.data('display'));
            next.disable();
            //
            let d = HouseUse.current || {};
            let spent = parseFloat(d.max_spent_monthly) - parseFloat(d.current_transc_amount);
            let paywith = parseFloat(el.data('value'));
            if (d.period && paywith && (spent >= paywith)) {
                if (limit == count) {
                    if (paywith >= parseFloat(balance.val())) {
                        next.enable();
                    }
                } else {
                    next.enable();
                }
            }
        });

        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
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
        let activePayment = payments.filter(function (i, e) {
            return ($(this).is(':visible'))
        });
        if (activePayment.length) {
            let limit = parseInt(modeCounter.val());
            let bayar = parseFloat(activePayment.find('[id*="-amount"]').data('value'));
            let nextBalance = balance.val() - parseFloat(bayar);
            let an = activePayment.find('[id*="-mode"]').find('select').val();
            let d = {};
            d.order_id = '95';
            if (an == 'cash') {
                d.payment_type_id = 1;
                d.payment_amount = activePayment.find('[id*="-cash-paywith"]').data('value');
                d.grandtotal = activePayment.find('[id*="-cash-amount"]').data('value');
                d.change_amount = activePayment.find('[id*="-cash-change"]').attr('val');
            } else if (an == 'card') {
                d.payment_type_id = activePayment.find('[id*="-card-card_type"]').val();
                d.payment_amount = activePayment.find('[id*="-card-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.change_amount = 0;
                d.card_no = activePayment.find('[id*="-cardno"]').val();
            } else if (an == 'charge2room') {
                d.payment_type_id = 16;
                d.payment_amount = activePayment.find('[id*="-charge-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.change_amount = 0;
                d.folio_id = activePayment.find('[id*="-charge-select"]').val();
            } else if (an == 'houseuse') {
                d.payment_type_id = 17;
                d.payment_amount = activePayment.find('[id*="-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.change_amount = 0;
                d.house_use_id = activePayment.find('[id*="-house-select"]').val();
            }

            if (nextBalance > 0) {
                next.disable();
                balance.val(nextBalance);
                balance.html(rupiahJS(nextBalance));
                recordList.append(recordPayment(count, bayar));

                console.log('saving & printing start #' + count);
                sendData(d, function (xhr, is) {
                    let res = xhr.responseJSON;
                    if (res && is == 'success') {
                        if (res.result == true) {
                            activePayment.hide();
                            next.enable();
                            next.click();
                            console.log('saving & printing done #' + count);
                            //
                            let title = 'CHK-29 /1 ';
                            let printPop = window.open(res.url, title, 'resizable,scrollbars,status');
                            printPop.focus();
                        } else {
                            alert(`Error occurrence, result : ${JSON.stringify(res.result)}`);
                        }
                    } else {
                        alert(`Error occurrence, result : ${JSON.stringify(is)}`);
                    }
                })
            } else if ((nextBalance <= 0) || (count == limit)) {
                next.disable();
                balance.val(nextBalance);
                balance.html(rupiahJS(nextBalance));
                recordList.append(recordPayment(count, bayar));

                console.log('saving & printing start #' + count);
                sendData(d, function (xhr, is) {
                    let res = xhr.responseJSON;
                    if (res && is == 'success') {
                        if (res.result == true) {
                            activePayment.hide();
                            mode.val('')
                            close.enable();
                            state.hide();
                            noState.hide();
                            console.log('saving & printing done #' + count);
                            console.log('finish splitting bills!');
                            //
                            let title = 'CHK-29 /1 ';
                            let printPop = window.open(res.url, title, 'resizable,scrollbars,status');
                            printPop.focus();
                        } else {
                            alert(`Error occurrence, result : ${JSON.stringify(res.result)}`);
                        }
                    } else {
                        alert(`Error occurrence, result : ${JSON.stringify(is)}`);
                    }
                })
            }
        } else {
            itemState.hide();
            eventState.hide();
            manualState.hide();
            count += 1;
            if (mode.val() == 'manual') {
                modeLabel0.html('Manual');
                modeLabel1.html(`${count} of ${modeCounter.val()}`);
                manualState.show();
                manualState.html('');
                manualState.append(payment(count));
            }
        }
    });
    mode.on('change', function () {
        let v = $(this).val();
        modeCounter.parent().prev().hide();
        modeCounter.hide();
        next.disable();
        close.disable();
        if (v) {
            if (v == 'manual') {
                modeCounter.parent().prev().show();
                modeCounter.show();
                if (parseFloat(modeCounter.val())) {
                    next.enable();
                }
            }
        }
    });
    modeCounter.on('change', function () {
        next.disable();
        if (mode.val() == 'manual' && parseFloat(modeCounter.val())) {
            next.enable();
        }
    })
    //
    m.on('show.bs.modal', function () {
        modeCounter.val('');
        recordList.html('');
        modeCounter.parent().prev().hide()
        modeCounter.hide();
        noState.show();
        state.hide();
        count = 0;
    });
    modeCounter.keyboard({layout: 'num'});
    m.modal({backdrop: 'static', keyboard: false});
    recordList.insertBefore(balance.closest('.row'));
    m.modal('hide');
};

$(document).ready(function () {
    let nextLocation;
    let paths = ["/main/merge/95"];
    let showCharge2RoomInfo = function (i) {
        let d = Charge2Room[i];
        $('#submitChargeToRoom').attr('disabled', 1);
        if (d) {
            $('input[name="folio_id"]').val(d.folio_id);
            $('#check_in_date').html(d.check_in_date);
            $('#departure_date').html(d.departure_date);
            $('#is_cash_bases').html(d.is_cash_bases);
            $('#is_room_only').html(d.is_room_only);
            $('#reservation_type').html(d.reservation_type);
            $('#room_no').html(d.room_no);
            $('#room_rate_code').html(d.room_rate_code + '/' + d.room_rate_name);
            $('#room_type').html(d.room_type + '/' + d.room_type_name);
            $('#vip_type').html(d.vip_type);
            if (d.is_cash_bases.toLowerCase() === 'n') {
                $('#submitChargeToRoom').removeAttr('disabled');
            }
        }
    };
    let showGuestUseInfo = function (i) {
        let d = HouseUse[i];
        $('#submitGuestUse').attr('disabled', 1);
        if (d) {
            $('input[name="house_use_id"]').val(d.house_use_id);
            $('#cost_center').html(d.cost_center);
            $('#house_use').html(d.house_use);
            $('#current_transc_amount').html(rupiahJS(d.current_transc_amount));
            $('#max_spent_monthly').html(rupiahJS(d.max_spent_monthly));
            $('#period').html(d.period);
            let balance = parseFloat(d.max_spent_monthly) - parseFloat(d.current_transc_amount);
            let grandtotal = $('#period').closest('.modal-body').find
            ('label[for="grandtot"]').attr('val');
            if (balance > 0) {
                if (d.period && (balance - parseFloat(grandtotal) >= 0)) {
                    $('#submitGuestUse').removeAttr('disabled');
                }
            }
        }
    };
    //
    splitBillsInit();
    $.keyboard.keyaction.enter = function (base) {
        if (base.el.id === "search_food") {
            base.accept();
            $('form').submit();
        }
    };
    $('input[type="text"]').keyboard({layout: 'qwerty'});
    $('textarea').keyboard({layout: 'qwerty'});
    //
    $('#cc_type').on('change', function () {
        let v = $(this).val();
        if (v == 'debit') {
            $('#card_name').parent().hide();
            $('#card_name').val('');
        } else {
            $('#card_name').parent().show();
        }
    });
    $("#card_swiper").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
        return
    });
    $('#card_swiper').on('keyup', function () {
        let el = $(this);
        delay(function () {
            let arr = swipeCard(el.val());
            $('#card_name').val('');
            if (arr.length) {
                $('#card_no').val(arr[0]);
                if ($('#cc_type').val() == 'credit') {
                    $('#card_name').val(arr[1]);
                } else {
                }
            }
            el.val('');
        }, 1000);
    });
    $('#card_no').on('change', function () {
        let val = $(this).val();
        next.disable();
        if (val && $('#card_name').val()) {
            next.enable();
        }
    });
    $('#card_name').on('change', function () {
        let val = $(this).val();
        next.disable();
        if (val && $('#card_no').val()) {
            next.enable();
        }
    });
    $('input[type="currency"]').on('blur', function () {
        let bayar = parseFloat($(this).data('value'));
        let grandtotal = parseFloat($('label[for="grandtot"]').attr('val'));
        let change = bayar - grandtotal;
        let displayChange = rupiahJS(change);
        $('label[for="change"]').html(displayChange);
        $('input[name="change_amount"]').val(change);
    });
    $("#search_food").keypress(function (e) {
        if (e.which == 13) {
            document.forms["myform"].submit();
            return false;    //<---- Add this line
        }
    });
    $('#charge_to_room').on('change', function () {
        Charge2Room.current = $(this).val();
        showCharge2RoomInfo(Charge2Room.current);
    });
    $('#guest_use').on('change', function () {
        HouseUse.current = $(this).val();
        showGuestUseInfo(HouseUse.current);
    });
    $('.list-group.checked-list-box .list-group-item').each(function () {
        // Settings
        let $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = (
                !$widget.data('style') || $widget.data('style') == "button" ? "btn-" : "list-group-item-"
            ),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            let isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
            .removeClass()
            .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }

            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }

        init();
    });
    $('label[for="grantotal-merge-me"]').css('margin-right', '20px');
    $("#mergeTable #check-list-box li").on('click', function (event) {
        event.preventDefault();
        let me = parseFloat($('label[for="grantotal-merge-me"]').attr('val'));
        let oth = 0;
        let paths_ = [];
        $("#mergeTable #check-list-box li.active").each(function (idx, li) {
            oth += parseFloat(mergeWith[li.id].due_amount);
            paths_.push(mergeWith[li.id].order_id)
        });
        $('#grantotal-merge').html(rupiahJS(me + oth));
        if (oth > 0) {
            nextLocation = paths.concat(paths_).join('-');
            $('#mergeTable').attr("action", nextLocation);
            $('#mergeTableBtn').removeAttr('disabled')
        } else {
            nextLocation = "";
            $('#mergeTable').attr("action", "#");
            $('#mergeTableBtn').attr('disabled', 1);
        }
    });
    $('#myModalPrint').on('show.bs.modal', function () {
        $('#print').show();
        $('#reprint').show();
        $('#manualprint').hide();
        $('#printtoogle').html('Manual Print');
        $('#printContentManual').hide();
        $('#printContentDefault').show();
        $('#print').removeAttr('disabled');
        $('#reprint').removeAttr('disabled');
        $('#manualprint').removeAttr('disabled');
    });
    $('#printtoogle').on('click', function () {
        if ($('#printtoogle').attr('state') == 'default') {
            $('#manualprint').show();
            $('#print').hide();
            $('#reprint').hide();
            $('#printtoogle').html('Back');
            $('#printtoogle').attr('state', 'manual');
            $('#printContentManual').show();
            $('#printContentDefault').hide();
        } else {
            $('#print').show();
            $('#reprint').show();
            $('#manualprint').hide();
            $('#printtoogle').html('Manual Print');
            $('#printtoogle').attr('state', 'default');
            $('#printContentManual').hide();
            $('#printContentDefault').show();
        }
    })
    $('#print').on('click', function () {
        $('#print').attr('disabled', 1);
        $.ajax({
            method: 'post',
            url: '/main/print_kitchen',
            data: {
                status: 0,
                order_id: '95',
            },
            complete: function (xhr, success) {
                $('#myModalPrint').modal('hide');
            }
        })
    });
    $('#reprint').on('click', function () {
        $('#reprint').attr('disabled', 1);
        $.ajax({
            method: 'post',
            url: '/main/print_kitchen',
            data: {
                status: 1,
                order_id: '95',
            },
            complete: function (xhr, success) {
                $('#myModalPrint').modal('hide');
            }
        })
    });
    $('#manualprint').on('click', function () {
        let active = $("#myModalPrint #check-list-box li.active");
        if (active.length) {
            $('#manualprint').attr('disabled', 1);
        }
        active.each(function (idx, li) {
            let id = $('li').attr('id').replace('printer-', '');
            let code = $('li').attr('code');
            let printer = $('li').attr('name');
            $.ajax({
                method: 'post',
                url: '/main/print_manual',
                data: {
                    order_id: '95',
                    printer
                },
                complete: function (xhr, success) {
                    $('#myModalPrint').modal('hide');
                }
            })
        });
    });
});