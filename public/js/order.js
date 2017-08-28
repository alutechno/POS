let isOlder, Menu, MealTime, MenuClass, MenuSubClass, Covers,
    Order, OrderMenu, Taxes, Summary, Discount, Payments = [],
    orderIds = window.location.pathname.split('/')[2].replace(/\-/g, ','),
    HouseUse = {}, Charge2Room = {}, CityLedger = {},
    El = {
        menu: $('div#menu-container'),
        order: $('div#order'),
        isPaid: $('div#is-paid'),
        numCover: $('div#num-cover'),
        mealTime: $('select#menu-meal-time'),
        menuClass: $('select#menu-class'),
        menuSubClass: $('select#menu-sub-class'),
        menuFinder: $('input#menu-finder'),
        orderMenu: $('table#order-menu'),
        tableSummary: $('table#order-summary'),
        tableSheets: $('table#sheets'),
        tableItems: $('table#items'),
        paymentBtn: $('a.payment-btn'),
        modalBillTip: $('div#modal-tip-billing'),
        modalQty: $('div#modal-qty'),
        modalCash: $('div#modal-cash'),
        modalCard: $('div#modal-card'),
        modalCharge2Room: $('div#modal-charge-to-room'),
        modalVoucher: $('div#modal-voucher'),
        modalCityLedger: $('div#modal-city-ledger'),
        modalNoPost: $('div#modal-no-post'),
        modalHouseUse: $('div#modal-house-use'),
        modalDiscountBill: $('div#modal-discount-bill'),
        modalMultiPayment: $('div#modal-multi-payment'),
        modalSplitBill: $('div#modal-split-billing'),
        modalAnySplitted: $('div#modal-any-splitted'),
        modalMergeBill: $('div#modal-merge'),
        modalPrintOrder: $('div#modal-print-order'),
        modalOpenMenu: $('div#modal-open-menu'),
        modalAddNote: $('div#modal-add-note'),
        modalVoid: $('div#modal-void'),
        modalVoidBill: $('div#modal-void-billing'),
        modalReprintBill: $('div#modal-reprint-billing'),
        btnShowDetail: $('a#show-detail'),
        btnAddTip: $('a#tip-billing'),
        btnPayCash: $('a#pay-cash'),
        btnPayCard: $('a#pay-card'),
        btnPayChargeToRoom: $('a#pay-charge-to-room'),
        btnPayHouseUse: $('a#pay-house-use'),
        btnPayCityLedger: $('a#pay-city-ledger'),
        btnPayVoucher: $('a#pay-voucher'),
        btnMultiPayment: $('a#pay-multi-payment'),
        btnSplitBilling: $('a#pay-split-bill'),
        btnPayNoPost: $('a#pay-no-post'),
        btnMergeBill: $('a#merge-bill'),
        btnItem2Sheet: $('button#to-right'),
        btnOpenCashDraw: $('button#open-cash-draw'),
        btnPrintBilling: $('button#print-billing'),
        btnReprintBilling: $('a#reprint-billing'),
        btnVoidBilling: $('a#void-billing'),
        btnDiscountBilling: $('a#discount-bill'),
        btnOrderNote: $('a#order-note'),
        btnPrintOrder: $('a#print-order'),
        btnOpenMenu: $('a#open-menu')
    };
let roleDetector = function (role1, role2, special) {
    let allow = role1 && App.role[role1] ? true : false;
    if (special || isOlder) {
        allow = role2 == 'FORCE' || (App.role[role2] && Payments.parent.isSameDay) ? true : false;
    }
    return allow;
};
let swipeCard = function (str) {
    return trim(str)
    .replace(/\/\s\^|\s\^/g, '/^')
    .split(/\;|\%B|\^|\/\^|\?\;|\=|\?/g)
    .slice(1, -1)
};
let getOpts = function (obj, isFloat) {
    var opts = {
        autoAccept: true,
        autoAcceptOnEsc:true,
        initialFocus:true,
        lockInput:false, //dont force disabled keyboard
        noFocus:false //force focus input!
    };
    if (!obj.hasOwnProperty('change')) {
        opts.change = function (ev, keyboard, el) {
            var val = $(keyboard.preview).val();
            if (obj.layout == 'numpad') {
                if (isFloat) {
                    $(el).data('value', parseFloat(val));
                    $(el).data('display', rupiahJS(val));
                    $(el).val(rupiahJS(val))
                } else {
                    $(el).data('value', val);
                    $(el).data('display', val);
                    $(el).val(val)
                }
            } else {
                $(el).val(val);
            }
            $(el).trigger('change');
        }
    }
    return Object.assign(opts, obj);
};
let loadPredifinedPromoId = function (data) {
    return {
        1: {
            id: 1,
            name: 'beverage',
            label: 'Beverage',
            value: data.beverage || 0
        },
        2: {
            id: 2,
            name: 'food',
            label: 'Food',
            value: data.food || 0
        },
        4: {
            id: 4,
            name: 'others',
            label: 'Others',
            value: data.others || 0
        }
    }
};
let delay = (function () {
    let timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();
let formalDate = function (param) {
    let dt = param.constructor == Date ? param : new Date(param);
    let y = dt.getFullYear();
    let m = dt.getMonth() > 8 ? dt.getMonth() + 1 : '0' + (dt.getMonth() + 1);
    let d = dt.getDate() > 9 ? dt.getDate() : '0' + dt.getDate();
    let h = dt.getHours() > 9 ? dt.getHours() : '0' + dt.getHours();
    let i = dt.getMinutes() > 9 ? dt.getMinutes() : '0' + dt.getMinutes();
    let s = dt.getSeconds() > 9 ? dt.getSeconds() : '0' + dt.getSeconds();
    return `${y}-${m}-${d} ${h}:${i}:${s}`;
};
let anySplitted = function () {
    let check = SQL(`SELECT * FROM pos_orders WHERE parent_id=?`, orderIds.split(',')[0]);
    return check.data;
}
let rupiahJS = function (val) {
    val = val || "0"
    return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
    .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
};
let openCashDraw = function () {
    if (!App.role.cashdraw) {
        El.btnOpenCashDraw.hide();
        return;
    }
    El.btnOpenCashDraw.show();
    El.btnOpenCashDraw.on('click', function () {
        $.ajax({
            method: 'GET',
            url: '/openCashDraw',
            async: false,
            complete: function (xhr, is) {
                if (is == 'success') {
                    if (xhr.responseJSON) {
                        if (!xhr.responseJSON.error) {
                            alert(xhr.responseJSON.message)
                            console.info(xhr.responseJSON.message);
                        } else console.error(xhr.responseJSON.message);
                    } else console.error(xhr.response);
                } else console.error('Server down!');
            }
        });
    });
};
let Printing = function (param, paymentType) {
    $.ajax({
        method: 'GET',
        url: '/printBill',
        async: false,
        data: param,
        complete: function (xhr, is) {
            if (is == 'success') {
                if (xhr.responseJSON) {
                    if (!xhr.responseJSON.error) console.info(xhr.responseJSON.message);
                    else console.error(xhr.responseJSON.message);
                } else console.error(xhr.response);
            } else console.error('Server down!');
        }
    });
};
let Payment = function (param, orderId) {
    orderId = orderId || orderIds.split(',')[0];
    let {
        order_notes, payment_type_id, card_type_id, payment_amount,
        tip_amount, change_amount, folio_id, card_no, house_use_id,
        total_amount, status, IS_MULTIPAY, MULTIPAY
    } = param;
    let userId = App.user.id;
    let tranBatchId = App.posCashier.id;
    let getDate = SQL('select NOW() now');
    let datetime = getDate.data[0].now;
    //
    let posOrders = {
        status: status || 2,
        order_notes,
        modified_by: userId,
        modified_date: formalDate(datetime),
        closing_batch_id: tranBatchId
    };
    let posOrdersArr = [];
    let posOrdersVal = [];
    for (let i in posOrders) {
        if (posOrders[i] === undefined) delete posOrders[i];
        else {
            posOrdersArr.unshift(i + '=?')
            posOrdersVal.unshift(posOrders[i])
        }
    }
    let updatePosOrder = SQL(`update pos_orders set ${posOrdersArr.join()} where id in (${orderIds})`, posOrdersVal);
    SQL(`select parent_id from pos_orders where id in (${orderIds})`);
    if (!updatePosOrder.error) {
        if (status === 4) {
            if (!param.hasOwnProperty('IS_MULTIPAY') || MULTIPAY == 'done') {
                Printing({orderId: orderId, splitted: true});
            }
            return {success: true, response: updatePosOrder.data};
        } else {
            let posPaymentDetail = {
                order_id: orderId,
                payment_type_id,
                card_type_id,
                payment_amount,
                tip_amount,
                change_amount,
                folio_id,
                card_no,
                house_use_id,
                total_amount,
                created_by: userId,
                status: 1
            };
            for (let i in posPaymentDetail) {
                if (posPaymentDetail[i] === undefined) delete posPaymentDetail[i];
            }
            if (isOlder) {
                if (IS_MULTIPAY) {
                    if (MULTIPAY == 1) {
                        SQL('update pos_payment_detail set status = 2 where order_id = ?', orderId);
                    }
                } else {
                    SQL('update pos_payment_detail set status = 2 where order_id = ?', orderId);
                }
            }
            let insertPosPaymentDetail = SQL('insert into pos_payment_detail set ?', posPaymentDetail);
            if (!insertPosPaymentDetail.error) {
                if (payment_type_id == 17) {
                    //todo: kalo pembayaran pake house use update ke akunting
                }
                if (!param.hasOwnProperty('IS_MULTIPAY')) {
                    Printing({
                        orderId: orderId,
                        paymentId: insertPosPaymentDetail.data.insertId
                    });
                } else if (MULTIPAY == 'done') {
                    let ids = SQL('select id from pos_payment_detail where order_id = ? and status = 1', orderId)
                    Printing({
                        orderId: orderId,
                        paymentId: ids.data.map(function(e){
                            return e.id
                        }),
                        splitted: true
                    });
                }
                if (payment_type_id == 11) El.btnOpenCashDraw.click();
                return {success: true, response: insertPosPaymentDetail.data};
            } else {
                return {success: false, response: insertPosPaymentDetail.error}
            }
        }
    } else {
        return {success: false, response: updatePosOrder.error}
    }
};
let getSummary = function (key, opt = {}) {
    let obj = {};
    let total = 0;
    let subtotal = 0;
    let discMenu = 0;
    let tax = 0;
    let taxes = opt.taxes || Taxes;
    let summary = opt.summary || Summary;
    let discount = opt.discount || Discount;
    let discountBill = discount[0].discount;
    taxes.forEach(function (el) {
        tax += el.tax_amount;
        obj[el.tax_name.toLowerCase().replace(/\s/g, '')] = {
            label: el.tax_name, value: el.tax_amount
        }
    });
    summary.forEach(function (el) {
        total += el.price;
        discMenu += el.discount;
    })
    subtotal = total - discountBill - discMenu;
    obj.discount = {label: 'Discount', value: discountBill + discMenu};
    obj.total = {label: 'Total', value: total};
    obj.subtotal = {label: 'Sub Total', value: subtotal};
    obj.grandtotal = {label: 'Grand Total', value: subtotal + tax};
    if (obj.hasOwnProperty(key)) return obj[key];
    return obj;
};
let goHome = function (param) {
    if (param.success) {
        setTimeout(function () {
            window.location.href = '/'
        }, 5000);
    } else {
        alert(JSON.stringify(param.response))
    }
};
let initCheckListBoxes = function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
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
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        function updateDisplay() {
            let isChecked = $checkbox.is(':checked');

            $widget.data('state', (isChecked) ? "on" : "off");
            $widget.find('.state-icon').removeClass().addClass('state-icon ' + settings[$widget.data('state')].icon);

            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }
        function init() {
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }

            updateDisplay();

            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }

        init();
    });
};
let loadBills = function () {
    let parent = SQL(`select * from pos_orders where id = ?`, orderIds.split(',')[0]);
    let getPayment = SQL(`
        select a.*, b.name
        from pos_payment_detail a
        join ref_payment_method b on a.payment_type_id = b.id
        where a.order_id = ? and a.status = 1
    `, orderIds.split(',')[0]);
    Payments = getPayment.data;
    isOlder = Payments.length;
    if (!Payments.length) {
        El.isPaid.hide()
    } else {
        let getDate = SQL('select NOW() now');
        let datetime = getDate.data[0].now;
        let span = El.isPaid.find('span');
        El.isPaid.show();
        Payments.parent = parent.data[0];
        Payments.parent.elapsed_time = new Date(datetime) - new Date(parent.data[0].modified_date);
        formalDate(datetime).split('\s')[0] == formalDate(parent.data[0].modified_date).split('\s')[0];
        Payments.parent.isSameDay = formalDate(datetime).split(/\s/g)[0] == formalDate(parent.data[0].modified_date).split(/\s/g)[0];

        if (Order[orderIds.split(',')[0]].status == '5') {
            span.addClass('btn-danger').html('Changed to void!');
        } else {
            span.addClass('btn-success').html('Already paid :)');
        }
    }
};
let loadCurrentMealTime = function () {
    let mealTime = SQL(`
        select id
        from ref_meal_time
        where
            status = 1 and
            DATE_FORMAT(now(), '%H:%i:%s') BETWEEN start_time and end_time LIMIT 1
    `);
    if (!mealTime.error) {
        let mealIn = mealTime.data[0] || {};
        if (mealIn.id) {
            El.mealTime.val(mealIn.id);
            El.mealTime.trigger('change');
        }
    }
};
let loadMealTime = function () {
    let mealTime = SQL(`select * from ref_meal_time where status = 1`);
    MealTime = {};
    if (!mealTime.error) {
        mealTime.data.forEach(function (e) {
            let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
            el.data(e);
            MealTime[e.id] = e;
            El.mealTime.append(el);
        });
    }
    El.mealTime.on('change', function () {
        loadMenu({mealtime: El.mealTime.val(), class: El.menuClass.val(), name: El.menuFinder.val()});
    });
};
let loadClass = function () {
    let menuClass = SQL(`select a.*
		from ref_outlet_menu_class a,pos_avail_menu_class b
		where a.id=b.menu_class_id and a.status=1 and b.outlet_Id=` + App.outlet.id + ' order by a.name');
    MenuClass = [];

    if (!menuClass.error) {
        El.menuClass.html(`<option value="">All</option>`);
        MenuClass = menuClass.data;
        menuClass.data.forEach(function (e) {
            let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
            el.data(e);
            El.menuClass.append(el)
        })
    }
    El.menuClass.on('change', function () {
        let val = El.menuClass.val();
        if (val) {
            El.menuSubClass.val('');
        }
        loadSubClass(val);
        loadMenu({mealtime: El.mealTime.val(), class: val, name: El.menuFinder.val()});
    })
};
let loadSubClass = function (filter) {
    if (filter === undefined) {
        let menuSubClass = SQL(`select c.*
			from ref_outlet_menu_class a,pos_avail_menu_class b,ref_outlet_menu_group c
			where a.id=b.menu_class_id
			and a.id=c.menu_class_id
			and c.status=1
			and b.outlet_Id=` + App.outlet.id + ' order by c.name');
        MenuSubClass = [];
        if (!menuSubClass.error) {
            El.menuSubClass.html(`<option value="">All</option>`);
            MenuSubClass = menuSubClass.data;
            menuSubClass.data.forEach(function (e) {
                let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
                el.data(e);
                El.menuSubClass.append(el)
            })
        }
        El.menuSubClass.on('change', function () {
            loadMenu({
                mealtime: El.mealTime.val(),
                class: El.menuClass.val(),
                subClass: El.menuSubClass.val(),
                name: El.menuFinder.val()
            })
        })
    } else {
        let filtered = MenuSubClass;
        if (filter) filtered = filtered.filter(function (e) {
            if (e.menu_class_id == filter) return 1;
            return 0
        });
        El.menuSubClass.html(`<option value="">All</option>`);
        if (filtered.length) {
            filtered.forEach(function (e) {
                let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
                el.data(e);
                El.menuSubClass.append(el)
            });
        }
    }
};
let loadMenu = function (filter) {
    if (!filter) {
        let query = App.mealTimeMenu ? ` and a.meal_time_id in (${Object.keys(MealTime).join()})` : ''
        let menu = SQL(`
            select
                b.name menu_class_name, b.code menu_class_code, a.*
            from inv_outlet_menus a
            left join ref_outlet_menu_class b on a.menu_class_id = b.id and b.status=1
            where a.status in ('O', 1) and a.outlet_id=${App.outlet.id} ${query} order by a.name
        `);
        let promos = (function () {
            let obj = {};
            let menuIds = menu.data.map(function (m) {
                return m.id
            })
            let day = SQL('select LOWER(DAYNAME(NOW())) a');
            let promo = SQL(`
                select * from pos_menu_promos
                where outlet_menu_id in(${menuIds.join()}) and
                is_avail_${day.data[0].a}='Y' and
                (DATE_FORMAT(now(), '%Y-%m-%d') between begin_date and end_date) and
                (DATE_FORMAT(now(), '%H:%i') between cast(begin_time as time) and cast(end_time as time))
            `);
            promo.data.forEach(function(prom){
                obj[prom.outlet_menu_id] = obj[prom.outlet_menu_id] || [];
                obj[prom.outlet_menu_id].push(prom)
            });
            return obj;
        })();
        Menu = [];
        if (!menu.error) {
            let modal = El.modalQty;
            let inputQty = modal.find('#qty');
            let discType = modal.find('#discount-type');
            let discPercent = modal.find('#discount-percent select');
            let discPercent2 = modal.find('#discount-percent-manual input');
            let discAmount = modal.find('#percent-amount');
            let discAmountItem = modal.find('#percent-amount-item');
            let labelPrice = modal.find('#price');
            let labelGross = modal.find('#gross');
            let labelNett = modal.find('#nett');
            let btnSubmit = modal.find('#submit');
            let menuBg = El.menu.find('.menu-bg');
            let menuFinder = El.menuFinder;
            //
            El.menu.html('');
            Menu = menu.data.map(function (e) {
                e.url_ = 'http://103.43.47.115:3000';
                e.menu_price_nett = e.menu_price;
                e.menu_price_ = rupiahJS(e.menu_price);
                e.menu_price_nett_ = rupiahJS(e.menu_price_nett);
                e.promos = promos[e.id] || [];
                e.total_discount = 0;
                e.promos.forEach(function (promo) {
                    let discount = 0;
                    if (promo.discount_amount > 0) {
                        discount = promo.discount_amount
                    } else if (promo.discount_percent > 0) {
                        discount = e.menu_price / 100 * promo.discount_percent;
                    }
                    e.total_discount += discount;
                    e.menu_price_nett -= discount;
                    e.menu_price_nett_ = rupiahJS(e.menu_price_nett)
                });
                let el = $(`
                    <div class="col-lg-2 col-sm-3 col-xs-3 menu-item"
                        menu-name="${e.name.toLowerCase()}"
                        menu-meal-time="${e.meal_time_id}"
                        menu-id="${e.id}"
                        menu-class="${e.menu_class_id}"
                        menu-sub-class="${e.menu_group_id}">
                        <div class="small-box">
                            <div class="small-box-footer menu">
                                <div class="menu-bg"></div>
                                <div class="menu-info text-center">
                                    <div class="menu-info-name">${e.name}</div>
                                    <div class="menu-info-price" style="${e.promos.length ? 'text-decoration: line-through;color:wheat':''}">
                                        &nbsp;&nbsp;${e.menu_price_}&nbsp;&nbsp;
                                    </div>
                                    ${e.promos.length ? `<div class="menu-info-price">${e.menu_price_nett_}</div>`:''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                el.data(e);
                if (e.image) {
                    el.find('.menu-bg').css('background-image', `url(${e.url_}/${e.image})`)
                } else {
                    el.find('.menu-bg').css('background-image', `url(${e.url_}/container/img/menu/food.jpg)`)
                }
                El.menu.append(el);
                return e;
            });
            //
            menuBg.height(menuBg.parent().width());
            menuFinder.keyboard(getOpts({layout: 'qwerty'}));
            menuFinder.off('change paste keyup input blur');
            menuFinder.on('change paste keyup input blur', function () {
                loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
            });
            //
            if (roleDetector('ordermenu', 'ordermenuafterpayment')) {
                let validation = function () {
                    let type = discType.val();
                    let percent = discPercent.val();
                    let price = modal.data('menu_price');
                    let qty = parseInt(inputQty.data('value'));
                    let max = price * qty;

                    discPercent2.parent().hide();
                    if (percent == 'manual') {
                        discPercent2.parent().show();
                        percent = discPercent2.data('value');
                    }
                    if (type == 'percent') {
                        let discount = price * percent / 100;
                        discPercent.parent().show();
                        discAmount.prop('disabled', true);
                        discAmount.data('value', discount * qty);
                        discAmount.data('display', rupiahJS(discount * qty));
                        discAmount.val(rupiahJS(discount * qty));

                        discAmountItem.prop('disabled', true);
                        discAmountItem.data('value', discount);
                        discAmountItem.data('display', rupiahJS(discount));
                        discAmountItem.val(rupiahJS(discount));
                    } else if (type == 'amount') {
                        discPercent.parent().hide();
                        discAmount.prop('disabled', false);
                        discAmountItem.prop('disabled', false);
                        if (this.id == 'percent-amount') {
                            let v = discAmount.data('value');
                            discAmountItem.data('value', v / qty);
                            discAmountItem.data('display', rupiahJS(v / qty));
                            discAmountItem.val(rupiahJS(v / qty));
                        } else if (this.id == 'percent-amount-item') {
                            let v = discAmountItem.data('value');
                            discAmount.data('value', v * qty);
                            discAmount.data('display', rupiahJS(v * qty));
                            discAmount.val(rupiahJS(v * qty));
                        }
                    }
                    //
                    btnSubmit.prop('disabled', true);
                    labelGross.html(rupiahJS(0));
                    labelNett.html(rupiahJS(0));
                    if (qty > 0 && (parseFloat(percent) <= 100)) {
                        labelGross.html(rupiahJS(max));
                        labelNett.html(rupiahJS(max - discAmount.data('value')));
                        if (discAmount.data('value') > -1 && discAmount.data('value') <= max) {
                            btnSubmit.prop('disabled', false);
                        }
                    }
                };
                El.menu.find('.menu-item').on('click', function () {
                    modal.find('h4').html($(this).data('name'));
                    modal.data($(this).data());
                    modal.modal('show');
                });
                discType.on('change', validation);
                discPercent.on('change', validation);
                discPercent2.on('change paste keyup input blur', validation);
                discAmountItem.on('change paste keyup input blur', validation);
                discAmount.on('change paste keyup input blur', validation);
                inputQty.on('change', validation)
                inputQty.on('blur', validation)
                btnSubmit.on('click', function () {
                    let data = Object.assign({}, modal.data());
                    if (discAmount.data('value')) {
                        let percent = discPercent.val();
                        if (percent == 'manual') {
                            percent = discPercent2.data('value');
                        }
                        data.addDiscount = {
                            percent: parseFloat(percent) || 0,
                            amount: discAmount.data('value')
                        }
                    }
                    addOrderMenu(data, parseInt(inputQty.data('value')))
                });
                modal.on('show.bs.modal', function () {
                    if (!roleDetector('ordermenu', 'ordermenuafterpayment')) {
                        return
                    }
                    let data = modal.data();
                    discPercent.html('');
                    discPercent.data('db').forEach(function (d, i) {
                        let code = data.menu_class_code;
                        let val = code = 'F' ? d.food : code = 'B' ? d.beverage : d.others;
                        let el = $(`<option value="${val}">${val} %</option>`);
                        if (!i) discPercent.append(`<option value="0">0 %</option>`);
                        discPercent.append(el);
                    });
                    discPercent.append(`<option value="manual">Manual</option>`);
                    labelPrice.html(data.menu_price_);
                    labelGross.html(rupiahJS(0));
                    discType.val('percent');
                    discPercent.parent().show();
                    discPercent.val('0');
                    discPercent2.data('value', 0);
                    discPercent2.data('display', rupiahJS(0));
                    discPercent2.val(rupiahJS(''));
                    discAmountItem.data('value', 0);
                    discAmountItem.data('display', rupiahJS(0));
                    discAmountItem.val('');
                    discAmount.data('value', 0);
                    discAmount.data('display', rupiahJS(0));
                    discAmount.val('');
                    inputQty.data('value', 0);
                    inputQty.data('display', rupiahJS(0));
                    inputQty.val('');
                    labelNett.html(rupiahJS(0));
                    btnSubmit.prop('disabled', true);
                });
            }
        }
    } else {
        let selector = '';
        if (filter.mealtime) selector += `[menu-meal-time=${filter.mealtime}]`;
        else selector += `[menu-meal-time]`;

        if (filter.class) selector += `[menu-class=${filter.class}]`;
        else selector += `[menu-class]`;

        if (filter.subClass) selector += `[menu-sub-class=${filter.subClass}]`;
        else selector += `[menu-sub-class]`;

        if (filter.name) selector += `[menu-name*='${filter.name.toLowerCase()}']`;
        else selector += `[menu-name]`;
        El.menu.find('.menu-item').hide();
        El.menu.find(selector).show();
    }
};
let loadOrder = function () {
    let cover = $(`<div style="border-left:1px solid rgba(128, 128, 128, 0.26);">`)
    let order = SQL(`
        select a.id, a.code, a.table_id, b.table_no, a.status, a.num_of_cover
        from pos_orders a, mst_pos_tables b
        where a.table_id=b.id
        and a.outlet_id=b.outlet_id and a.id in(${orderIds})
    `);
    if (!order.error) {
        Covers = 0;
        Order = {};
        El.order.html('<code style="margin-right: 5px">Order Number / Table</code>');
        order.data.forEach(function (e) {
            Order[e.id] = e;
            Covers += e.num_of_cover;
            El.order.append(`
                <label class="badge btn-primary" style="font-weight: lighter; margin-right: 5px">
                    ${e.code} / ${e.table_no}
                </label>
            `)
        });
        cover.append(`
            <code style="margin-right: 5px">Num Covers</code>
            <label class="badge btn-info" style="font-weight: lighter">
                ${Covers}
            </label>
        `);
        El.numCover.html(cover);
    }

};
let loadOrderMenu = function () {
    let mVoid, m = El.modalVoid;
    let inputQty = m.find('input');
    let btnSubmit = m.find('#submit');
    let orderMenu = SQL(`
        select * from (
            select
                1 type, true is_parent, a.id line_item_id, a.id parent_id, a.order_id,
                a.menu_class_id, a.outlet_menu_id, aa.name, a.serving_status, a.order_qty,
                a.price_amount, null promo_id, null discount_percent,
                null discount_amount, a.total_amount, a.created_date
            from pos_orders_line_item a
            left join inv_outlet_menus aa on aa.id = a.outlet_menu_id
            where a.parent_id is null and order_id in (${orderIds})
            union
            select * from (
                select
                    3 type, false is_parent, x.order_line_item_id line_item_id,
                    ifnull(y.parent_id, x.order_line_item_id) parent_id,
                    y.order_id, y.menu_class_id, y.outlet_menu_id, z.name,
                    0 serving_status, 0 order_qty, y.price_amount, x.promo_id,
                    cast(x.discount_percent as char) discount_percent,
                    cast(sum(x.discount_amount) as char) discount_amount,
                    sum(x.discount_amount)*-1 total_amount, x.created_date
                from pos_patched_discount x
                left join pos_orders_line_item y on y.id = x.order_line_item_id
                left join inv_outlet_menus z on z.id = y.outlet_menu_id
                where y.order_id in (${orderIds})
                group by ifnull(y.parent_id, x.order_line_item_id), promo_id
                order by ifnull(y.parent_id, x.order_line_item_id), order_line_item_id,
                created_date desc
            ) b
            union
            select * from (
                select
                    2 type, is_parent, id line_item_id, parent_id, order_id, menu_class_id,
                    outlet_menu_id, name, serving_status, sum(order_qty) order_qty, price_amount,
                    null promo_id, null discount_percent, null discount_amount,
                    sum(total_amount) total_amount, created_date
                from (
                    select
                        false is_parent, r.name, pos_orders_line_item.*
                    from pos_orders_line_item
                    left join inv_outlet_menus r on r.id = pos_orders_line_item.outlet_menu_id
                    where parent_id is not null and order_id in (${orderIds})
                    order by created_date desc
                ) _ where _.parent_id is not null
                group by parent_id order by created_date desc
            ) c
        ) o
        where total_amount != 0
        order by parent_id, type;
    `);
    OrderMenu = [];
    loadBills();
    if (!orderMenu.error) {
        let no = 0;
        let getVoid = function (parent_id) {
            return orderMenu.data.filter(function (dd) {
                return ((dd.parent_id == parent_id) && (dd.type == 2)) ? 1 : 0
            })
        };
        let getAddDiscount = function (parent_id) {
            return orderMenu.data.filter(function (dd) {
                return ((dd.parent_id == parent_id) && (dd.type == 3) && (!dd.promo_id)) ? 1 : 0
            })
        };
        let getBasicDiscount = function (parent_id) {
            return orderMenu.data.filter(function (dd) {
                return ((dd.parent_id == parent_id) && (dd.type == 3) && (dd.promo_id)) ? 1 : 0
            })
        };
        orderMenu.data.forEach(function (d) {
            let e = Object.assign({}, d)
            if (e.type == 1) {
                let voiD = getVoid(e.parent_id)[0];
                let addDisc = getAddDiscount(e.parent_id)[0] || {};
                let basicDisc = getBasicDiscount(e.parent_id)[0] || {};
                e.is = 'order';
                e.name_ = e.name;
                e.no = ++no;
                e.order_qty_ = e.order_qty;
                e.addDiscount = {};
                e.basicDiscount = {};
                if (addDisc.discount_amount && addDisc.discount_percent) {
                    e.addDiscount.type = 'percent';
                    e.addDiscount.percent = parseFloat(addDisc.discount_percent);
                    e.addDiscount.amount = parseFloat(addDisc.discount_amount);
                } else {
                    e.addDiscount.type = 'amount';
                    e.addDiscount.percent = null;
                    e.addDiscount.amount = parseFloat(addDisc.discount_amount);
                }
                if (basicDisc.discount_amount && basicDisc.discount_percent) {
                    e.basicDiscount.type = 'percent';
                    e.basicDiscount.percent = parseFloat(basicDisc.discount_percent);
                    e.basicDiscount.amount = parseFloat(basicDisc.discount_amount);
                } else {
                    e.basicDiscount.type = 'amount';
                    e.basicDiscount.percent = null;
                    e.basicDiscount.amount = parseFloat(basicDisc.discount_amount);
                }
                e.order_void = voiD ? voiD.order_qty * -1 : 0;
            } else if (e.type == 3) {
                let discount = parseFloat(e.discount_percent);
                e.is = 'discount';
                e.name_ = 'Discount' + (discount ? ` ${parseFloat(e.discount_percent)}%` : '');
                e.order_qty = '';
                e.order_qty_ = '';
                e.no = '';
            } else {
                e.is = 'void';
                e.name_ = 'Void';
                e.no = '';
                e.order_qty_ = e.order_qty * -1;
            }
            e.total_amount_ = rupiahJS(e.total_amount);
            OrderMenu.push(e);
        });
    }
    window.miscFn0 = function (row, index) {
        return row.is == 'order' ? { classes: 'btn-default'} : {};
    };
    window.miscFn1 = function (value, row, index) {
        let isOrder = row.is == 'order';
        let voidable = row.order_qty > row.order_void;
        if (isOrder && voidable) {
            return !roleDetector('voidmenu', 'voidmenuafterpayment') ? '' : `<div class="pull-right">
                <a class="remove" href="#modal-void" title="Void">
                    <i class="glyphicon glyphicon-remove text-danger"></i>
                </a>
            </div>`
        }
        return '';
    };
    window.miscFn1Cfg = {
        'click .remove': function (e, value, row) {
            m.modal('show');
            m.find('#modal-label').html(row.name)
            inputQty.val('');
            btnSubmit.prop('disabled', true);
            mVoid = row;
        }
    };
    inputQty.off('change paste keyup input blur');
    inputQty.on('change paste keyup input blur', function () {
        let val = inputQty.data('value');
        btnSubmit.prop('disabled', true);
        val = parseInt(val);
        if (val) {
            if ((mVoid.order_qty - mVoid.order_void) >= val) {
                btnSubmit.prop('disabled', false);
            }
        }
    });
    btnSubmit.off('click');
    btnSubmit.on('click', function () {
        let val = parseInt(inputQty.data('value')) * -1
        let {type, amount, percent} = mVoid.addDiscount;
        let qty = mVoid.order_qty - mVoid.order_void;
        let discount = amount/qty
        let data = {
            id: mVoid.outlet_menu_id,
            parent_id: mVoid.line_item_id,
            menu_class_id: mVoid.menu_class_id,
            outlet_id: App.outlet.id,
            menu_price: mVoid.price_amount,
            name: mVoid.name
        }
        if (type) {
            let addDiscount = { amount: discount * val }
            if (type == 'percent') addDiscount.percent = percent
            data.addDiscount = addDiscount
        }
        //
        btnSubmit.prop('disabled', true);
        addOrderMenu(data, val, mVoid.order_id);
        m.modal('hide');
    });
    m.on('show.bs.modal', function () {
        btnSubmit.prop('disabled', true);
    });
    El.orderMenu.bootstrapTable('load', OrderMenu);
    El.btnShowDetail.attr('disabled', 1);
    El.btnShowDetail.off('click');
    El.btnShowDetail.on('click', function(){
        let next = El.btnShowDetail.attr('next');
        El.orderMenu.bootstrapTable('getData').forEach(function (e, i) {
            if (e.is !== 'order') {
                if (next == 'collapse') {
                    El.orderMenu.bootstrapTable('showRow', {index: i});
                } else {
                    El.orderMenu.bootstrapTable('hideRow', {index: i});
                }
            }
        });
        if (next == 'collapse') {
            El.btnShowDetail.attr('next', 'expand');
            El.btnShowDetail.html('<i class="fa fa-list"></i> Collapse');
        } else {
            El.btnShowDetail.attr('next', 'collapse');
            El.btnShowDetail.html('<i class="fa fa-list"></i> Expand');
        }
    });
    El.orderMenu.bootstrapTable('getData').forEach(function (e, i) {
        let mode = El.btnShowDetail.attr('next');
        if (e.is !== 'order') {
            if (mode == 'collapse') {
                El.orderMenu.bootstrapTable('hideRow', {index: i});
            } else {
                El.orderMenu.bootstrapTable('showRow', {index: i});
            }
        }
    });
    if (OrderMenu.length) {
        El.btnShowDetail.removeAttr('disabled');
        El.paymentBtn.removeAttr('disabled');
        El.btnPrintBilling.removeAttr('disabled');
        El.btnOrderNote.removeAttr('disabled');
        El.btnPrintOrder.removeAttr('disabled');
    }
};
let loadTotal = function (id) {
    let ids = [].concat(id);
    let queries = SQL(`
        select
            tax_id, sum(a.tax_amount) tax_amount, b.name tax_name
        from pos_order_taxes a, mst_pos_taxes b
        where a.tax_id=b.id and a.order_id in (${ids.join()})
        group by tax_id;

        select
            b.name, sum(a.total_amount) price, (
                select sum(a.discount_amount) discount
                from pos_patched_discount a
                join pos_orders_line_item b on b.id = a.order_line_item_id
                where b.order_id in (${ids.join()})
            ) discount
        from pos_orders_line_item a
        join ref_outlet_menu_class b on b.id = a.menu_class_id
        where a.order_id in (${ids.join()})
        group by b.name;

        select 'Discount bill' name, sum(a.discount_amount) discount
        from pos_patched_discount a
        where a.order_id in (${ids.join()});
    `);
    let taxes = queries.data[0];
    let summary = queries.data[1];
    let discount = queries.data[2];
    return {taxes, summary, discount};
};
let loadOrderSummary = function (id) {
    let total = loadTotal(id);
    let taxes = Taxes = total.taxes;
    let summary = Summary = total.summary;
    let discount = Discount = total.discount;
    let sum = getSummary(0, total);
    let parent = El.tableSummary.find('tbody')

    parent.html('');
    summary.forEach(function (el) {
        if (el.name) parent.append(`
            <tr class="info text-italic">
                <td colspan="3" align="left" style="font-style: italic;">${el.name}</td>
                <td align="right">${rupiahJS(el.price)}</td>
            </tr>
        `)
    });
    ['total', 'discount', 'subtotal', 'servicecharge', 'tax', 'grandtotal'].forEach(function (e) {
        let padder = ''
        let {label, value} = sum[e];
        let row = $(`
            <tr>
                <td colspan="3" align="left">${label}</td>
                <td align="right">${rupiahJS(value)}</td>
            </tr>
        `)
        if (e.indexOf('total') < 0) {
            $(row.find('td')[0]).css('padding-left', '25px');
        }
        parent.append(row);
    });
};
let loadOrderSummary4modal = function ({total, discount, subtotal, servicecharge, tax, grandtotal}) {
    let el = $(`
        <div class="row">
            <div class="col-lg-6">
                <label>Total</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="total">
                    ${rupiahJS(total.value)}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label style="margin-left: 15px;">Discount</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="discount">
                    ${rupiahJS(discount.value)}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label>Sub Total</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="subtotal">
                    ${rupiahJS(subtotal.value)}
                </label>
            </div>
        </div>
        <div class="row discount-exception">
            <div class="col-lg-6">
                <label style="margin-left: 15px;">Service Charge</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="service">
                    ${rupiahJS(servicecharge.value)}
                </label>
            </div>
        </div>
        <div class="row discount-exception">
            <div class="col-lg-6">
                <label style="margin-left: 15px;">Tax</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="tax">
                    ${rupiahJS(tax.value)}
                </label>
            </div>
        </div>
        <div class="row discount-exception">
            <div class="col-lg-6">
                <label>Grand Total</label>
            </div>
            <div class="col-lg-6 text-right">
                <label style="margin-right: 13px;" for="grandtotal">
                    ${rupiahJS(grandtotal.value)}
                </label>
            </div>
        </div>
        <hr style="margin-top: 5px; margin-bottom: 5px"/>
    `);
    el.find('[for="grandtotal"]').data({
        value: grandtotal.value,
        display: rupiahJS(grandtotal.value)
    });
    return el;
};
let addOrderMenu = function (data, qty = 1, orderId) {
    orderId = orderId || orderIds.split(',')[0];
    let {id, parent_id, menu_class_id, outlet_id, menu_price, addDiscount} = data;
    let amount = menu_price * qty;
    let totalDiscount = 0;
    let orderItemId, patchDiscountIds = [], orderTaxIds = {}, posOrder;
    let getDate = SQL('select NOW() now');
    //
    let addOrderItem = function () {
        let newLineItem = {
            parent_id: parent_id,
            order_id: orderId,
            menu_class_id: menu_class_id,
            outlet_menu_id: id,
            serving_status: 0,
            order_qty: qty,
            price_amount: menu_price,
            total_amount: amount,
            created_by: App.user.id
        }
        if (isOlder) {
            newLineItem.void_notes = "void menu after payment";
            newLineItem.void_date = getDate.data[0].now;
            newLineItem.void_by = App.user.id;
        }
        let orderItem = SQL('insert into pos_orders_line_item set ?', newLineItem);
        orderItemId = orderItem.data.insertId;
    };
    let addDiscountPatched = function () {
        let day = SQL('select LOWER(DAYNAME(NOW())) a');
        let promos = SQL(`
            select * from pos_menu_promos
            where outlet_menu_id=? and
            is_avail_${day.data[0].a}='Y' and
            (DATE_FORMAT(now(), '%Y-%m-%d') between begin_date and end_date) and
            (DATE_FORMAT(now(), '%H:%i') between cast(begin_time as time) and cast(end_time as time))
        `, id);
        promos.data.forEach(function (promo) {
            let discount = 0;
            if (promo.discount_amount > 0) {
                discount = promo.discount_amount
            } else if (promo.discount_percent > 0) {
                discount = menu_price / 100 * promo.discount_percent;
            }
            discount = discount * qty;
            totalDiscount += discount;

            let newPatchDiscount = {
                order_line_item_id: orderItemId,
                menu_class_id: menu_class_id,
                outlet_menu_id: id,
                promo_id: promo.id,
                discount_amount: discount,
                created_by: App.user.id
            }
            let patchDiscount = SQL('insert into pos_patched_discount set ?', newPatchDiscount);
            patchDiscountIds.push(patchDiscount.data.insertId);
        });
        if (addDiscount) {
            if (addDiscount.amount) {
                let newPatchDiscount = {
                    order_line_item_id: orderItemId,
                    menu_class_id: menu_class_id,
                    outlet_menu_id: id,
                    discount_percent: addDiscount.percent,
                    discount_amount: addDiscount.amount,
                    created_by: App.user.id
                }
                let patchDiscount = SQL('insert into pos_patched_discount set ?', newPatchDiscount);
                patchDiscountIds.push(patchDiscount.data.insertId);
                totalDiscount += addDiscount.amount;
            }
        }
    };
    let updateOrderTaxes = function () {
        let amount_w_discount = amount - totalDiscount;
        let outletTaxes = SQL('select is_sevice_included, is_tax_included from inv_outlet_menus where id = ?', id);
        let {is_tax_included, is_sevice_included} = outletTaxes.data[0];
        let orderTaxes = SQL('select * from pos_order_taxes where order_id = ?', orderId);
        if (is_tax_included != 'Y' || is_sevice_included != 'Y') {
            if (is_tax_included != 'Y') {
                let rows = orderTaxes.data.filter(function (row) {
                    return row.tax_id == 2 ? 1 : 0
                });
                if (rows[0]) {
                    let taxAmount = rows[0].tax_amount + (amount_w_discount * rows[0].tax_percent / 100);
                    let updateOrderTax2 = SQL(
                        'update pos_order_taxes set tax_amount = ? where id = ? and tax_id = ?',
                        [taxAmount, rows[0].id, rows[0].tax_id]
                    );
                    orderTaxIds[rows[0].id] = rows[0].tax_amount;
                }
            }
            if (is_sevice_included != 'Y') {
                let rows = orderTaxes.data.filter(function (row) {
                    return row.tax_id == 1 ? 1 : 0
                });
                if (rows[0]) {
                    let taxAmount = rows[0].tax_amount + (amount_w_discount * rows[0].tax_percent / 100);
                    let updateOrderTax1 = SQL(
                        'update pos_order_taxes set tax_amount = ? where id = ? and tax_id = ?',
                        [taxAmount, rows[0].id, rows[0].tax_id]
                    );
                    orderTaxIds[rows[0].id] = rows[0].tax_amount;
                }
            }
            //todo: additional tax & service
        }
    };
    let updateItem = function () {
        let selectOrder = SQL('select * from pos_orders where id = ?', orderId);
        let {sub_total_amount, tax_total_amount, due_amount, discount_total_amount} = selectOrder.data[0];
        let taxes = SQL('select sum(tax_amount) tax from pos_order_taxes where order_id=?', orderId)
        let totalTaxes = taxes.data[0].tax;
        //
        posOrder = selectOrder.data[0]
        sub_total_amount += amount;
        discount_total_amount += totalDiscount;
        //
        let posOrderUpdate = {
            sub_total_amount: sub_total_amount,
            discount_total_amount: discount_total_amount,
            tax_total_amount: totalTaxes,
            due_amount: sub_total_amount - discount_total_amount + totalTaxes
        }
        if (isOlder) {
            posOrderUpdate.reopen_notes = "void menu after payment";
            posOrderUpdate.reopen_date = getDate.data[0].now;
            posOrderUpdate.reopen_by = App.user.id;
        }
        let obj = Object.keys(posOrderUpdate)
        SQL(`update pos_orders set ${
            obj.map(function (key) { return key + '=?' }).join()
        } where id=${orderId}`, obj.map(function (key) {
            return posOrderUpdate[key]
        }));
    };

    addOrderItem();
    addDiscountPatched();
    updateOrderTaxes();
    updateItem();
    loadOrderMenu();
    loadOrderSummary(orderIds);
    El.modalQty.modal('hide');
};
let mergeBill = function () {
    if (!roleDetector('mergebill', 'mergebillafterpayment')) {
        El.btnMergeBill.hide();
        return;
    }
    let modal = El.modalMergeBill;
    let pattern = '#check-list-box li';
    let grandtotal = getSummary('grandtotal').value;
    let ulCheckListBox = modal.find('#check-list-box');
    let lblHomeTotal = modal.find('#home-total');
    let lblGrandtotal = modal.find('#grandtotal');
    let btnSubmit = modal.find('#submit');
    let paths = orderIds.split(',');
    //
    El.btnMergeBill.show();
    //
    let tableList = SQL(`
        select
            b.id, b.table_no, b.cover, a.id order_id, a.num_of_cover guest,
            a.sub_total_amount, a.discount_total_amount, a.tax_total_amount,
            a.due_amount, a.code order_code
        from pos_orders a
        join mst_pos_tables b on b.id = a.table_id
        where a.status not in (2,6) and a.id not in (${orderIds})
        order by b.table_no, a.id
    `, App.outlet.id);
    ulCheckListBox.html('');
    if (!tableList.data.length) {
        El.btnMergeBill.attr('disabled', true);
    }
    tableList.data.forEach(function (e) {
        let li = $(`
            <li id="merge-w-${e.order_id}" class="list-group-item" data-color="info">
                Table ${e.table_no} / ${e.order_code}
                <span class='badge badge-default badge-pill'>${rupiahJS(e.due_amount)}</span>
            </li>
        `);
        li.data('!', e);
        ulCheckListBox.append(li)
    });
    lblHomeTotal.html(rupiahJS(grandtotal));
    lblGrandtotal.html(rupiahJS(grandtotal));
    btnSubmit.prop('disabled', true);
    modal.find(pattern).on('click', function (event) {
        event.preventDefault();
        let a = [];
        let to = setTimeout(function () {
            let others = 0;
            let actives = modal.find(pattern + '.active');
            grandtotal = getSummary('grandtotal').value;
            actives.each(function (idx, li) {
                let data = $(this).data('!');
                others += parseFloat(data.due_amount || 0);
                a.push(data.order_id);
            });
            lblGrandtotal.html(rupiahJS(parseFloat(grandtotal) + others));
            if (actives.length) {
                btnSubmit.prop('disabled', false);
            } else {
                btnSubmit.prop('disabled', true);
            }
            paths = orderIds.split(',').concat(a)
        }, 500)
    });
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let home = paths[0];
        let keys = paths.slice(1);
        let getDate = SQL('select NOW() now');
        let datetime = getDate.data[0].now;
        keys.forEach(function (key) {
            SQL(
                `insert into pos_included_orders set order_id=?,included_order_id=?,created_by=? on duplicate key update modified_date=?,modified_by=?`,
                [home, key, App.user.id, formalDate(datetime), App.user.id]
            )
        });
        window.location.href = '/order/' + paths.join('-')
    });
    //
    $('a[href="#modal-merge"]').on('click', function () {
        El.modalMergeBill.find('li.active').click();
    })
};
let addBillTip = function () {
    if (!roleDetector('addtip', 'addtipafterpayment')) {
        El.btnAddTip.hide();
        return;
    }
    let m = El.modalBillTip;
    let labelBefore = m.find('label[for="before"]');
    let labelAfter = m.find('label[for="after"]');
    let inputAmount = m.find('input#amount');
    let btnSubmit = m.find('#submit');
    let orderId = orderIds.split(',')[0];
    let userId = App.user.id;
    let validation = function () {
        let before = labelBefore.data('value');
        let value = inputAmount.data('value');
        let total = parseFloat(before) + parseFloat(value);
        labelAfter.html(rupiahJS(total));
        if (total >= 0) {
            btnSubmit.prop('disabled', false);
        } else {
            btnSubmit.prop('disabled', true);
        }
    };

    El.btnAddTip.show();
    inputAmount.on('change paste keyup input blur', validation);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let tip_amount = inputAmount.data('value');
        let posPaymentDetail = {
            order_id: orderId,
            tip_amount,
            created_by: userId,
            status: 1
        };
        let insertPosPaymentDetail = SQL('insert into pos_payment_detail set ?', posPaymentDetail);
        m.modal('hide')
    });
    m.on('show.bs.modal', function () {
        btnSubmit.prop('disabled', true);
        let tipRaw = SQL(`select ifnull(sum(tip_amount), 0) tip from pos_payment_detail where order_id = ? and status = 1`, orderId);
        let tip = tipRaw.data[0].tip || 0;
        inputAmount.data('value', 0);
        inputAmount.data('display', '');
        inputAmount.val('');
        labelBefore.data('value', tip);
        labelBefore.html(rupiahJS(tip));
        labelAfter.html(rupiahJS(tip));
        btnSubmit.prop('disabled', true);
    });
};
let cashPayment = function () {
    if (!roleDetector('cash', 'cashafterpayment')) {
        El.btnPayCash.hide();
        return;
    }
    let modal = El.modalCash;
    let inputTip = modal.find('#add-tip');
    let btnSubmit = modal.find('#submit');
    let lblChange = modal.find('#change');
    let inputAmount = modal.find('#amount');
    let total, discount, service, tax, grandtotal, change;
    let validation = function () {
        let value = inputAmount.data('value');
        change = parseFloat(value) - parseFloat(grandtotal) - inputTip.data('value');
        if (change >= 0 && inputTip.data('value') >= 0) {
            btnSubmit.prop('disabled', false);
            lblChange.html(rupiahJS(change));
        } else {
            btnSubmit.prop('disabled', true);
            lblChange.html(rupiahJS(0));
        }
    }
    //
    El.btnPayCash.show();
    inputTip.on('change paste keyup input blur', validation);
    inputAmount.on('change paste keyup input blur', validation);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: 11,
            grandtotal: grandtotal,
            tip_amount: inputTip.data('value'),
            payment_amount: inputAmount.data('value'),
            change_amount: change,
        });
        goHome(pay);
    });
    modal.on('show.bs.modal', function () {
        btnSubmit.prop('disabled', true);
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
        change = 0;
        inputTip.data('value', 0);
    });
};
let cardPayment = function () {
    if (!roleDetector('card', 'cardafterpayment')) {
        El.btnPayCard.hide();
        return;
    }
    let modal = El.modalCard;
    let inputTip = modal.find('#add-tip');
    let selectEDC = modal.find('#bank-type');
    let selectBankType = modal.find('#bank-account');
    let selectCcType = modal.find('#cc-type');
    let inputCardSwiper = modal.find('#card-swiper');
    let inputCardNo = modal.find('#card-no');
    let inputCustomerName = modal.find('#customer-name');
    let btnSubmit = modal.find('#submit');
    //
    El.btnPayCard.show();
    //
    let total, discount, service, tax, grandtotal;
    let validation = function () {
        let val1 = selectEDC.val();
        let val2 = inputCardNo.val();
        let val3 = inputCustomerName.val();
        let val4 = inputTip.data('value') >= 0;
        btnSubmit.prop('disabled', true);
        if (selectCcType.val() == 'credit') {
            if (val1 && val3 && val3 && val4) btnSubmit.prop('disabled', false);
        } else {
            inputCustomerName.val('');
            if (val1 && val2) btnSubmit.prop('disabled', false);
        }
    };
    let edcList = SQL(`select id, code, name, description from ref_payment_method where category = 'CC' and status = '1' order by name`);
    let bankList = SQL(`select id, code, name, description from mst_credit_card where status = '1' order by name`);
    selectEDC.html('<option value="">- Choose -</option>');
    selectBankType.html('<option value="">- Choose -</option>');
    edcList.data.forEach(function (edc) {
        selectEDC.append(`<option value="${edc.id}">${edc.code} - ${edc.name}</option>`);
    });
    bankList.data.forEach(function (bank) {
        selectBankType.append(`<option value="${bank.id}">${bank.code} - ${bank.name}</option>`);
    });
    modal.on('show.bs.modal', function () {
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
        inputTip.data('value', 0);
    });
    inputCardSwiper.keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
        return
    });
    inputCardSwiper.on('keyup', function () {
        let el = $(this);
        delay(function () {
            let arr = swipeCard(el.val());
            inputCustomerName.val('');
            if (arr.length) {
                inputCardNo.val(arr[0]);
                if (selectCcType.val() == 'credit') {
                    inputCustomerName.val(arr[1]);
                } else {
                }
            }
            el.val('');
        }, 1000);
    });
    selectCcType.on('change', validation);
    inputTip.on('change paste keyup input blur', validation);
    inputCardNo.on('change paste keyup input blur', validation);
    inputCustomerName.on('change paste keyup input blur', validation);
    selectEDC.on('change', validation);
    selectBankType.on('change', validation);
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: selectEDC.val(),
            card_type_id: selectBankType.val(),
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            tip_amount: inputTip.data('value'),
            change_amount: 0
        });
        goHome(pay);
    });
};
let chargeToRoomPayment = function () {
    if (!roleDetector('chargeroom', 'chargeroomafterpayment')) {
        El.btnPayChargeToRoom.hide();
        return;
    }
    let modal = El.modalCharge2Room;
    let inputTip = modal.find('#add-tip');
    let selectCustomer = modal.find('#customer');
    let lblCheckInDate = modal.find('#check-in-date');
    let lblDepartureDate = modal.find('#departure-date');
    let lblIsCashBases = modal.find('#is-cash-bases');
    let lblIsRoomOnly = modal.find('#is-room-only');
    let lblReservationType = modal.find('#reservation-type');
    let lblRoomNo = modal.find('#room-no');
    let lblRoomRateCode = modal.find('#room-rate-code');
    let lblRoomType = modal.find('#room-type');
    let lblVipType = modal.find('#vip-type');
    let txAreaNote = modal.find('#note');
    let firstName = modal.find('#ctr-first-name');
    let lastName = modal.find('#ctr-last-name');
    let roomNo = modal.find('#ctr-room-number');
    let btnSubmit = modal.find('#submit');
    let validation = function () {
        let val1 = selectCustomer.val();
        let val2 = inputTip.data('value') >= 0;
        let val3 = firstName.val();
        let val4 = roomNo.val();

        data = 0;
        btnSubmit.prop('disabled', true);
        //if (val1 && val2) { //REMARK until integrate to FO
        if (val3 && val4 && val2) {
            //REMARK until integrate to FO
            /*data = houseGuest.data.filter(function (e) {
                return e.folio_id == val1 ? 1 : 0;
            })[0];
            folio_id = (data.folio_id);
            lblCheckInDate.html(data.check_in_date);
            lblDepartureDate.html(data.departure_date);
            lblIsCashBases.html(data.is_cash_bases);
            lblIsRoomOnly.html(data.is_room_only);
            lblReservationType.html(data.reservation_type);
            lblRoomNo.html(data.room_no);
            lblRoomRateCode.html(data.room_rate_code + '/' + data.room_rate_name);
            lblRoomType.html(data.room_type + '/' + data.room_type_name);
            lblVipType.html(data.vip_type);*/
            /*if (data.is_cash_bases.toLowerCase() === 'n') {
                btnSubmit.prop('disabled', false);
            }*/
            console.log('asd',val3,val4)
            if (val3.length>0 && val4.length>0){
                console.log('masuk')
                btnSubmit.prop('disabled', false);
            }


        }
    }
    //
    El.btnPayChargeToRoom.show()
    //
    let total, discount, service, tax, grandtotal, folio_id, data;
    let houseGuest = SQL(`select * from v_in_house_guest`);
    selectCustomer.html('<option value="">- Choose -</option>');
    houseGuest.data.forEach(function (e) {
        Charge2Room[e.folio_id] = e;
        let el = $(`<option value="${e.folio_id}">[${e.room_type} / ${e.room_no}] - ${e.cust_firt_name} ${e.cust_last_name}</option>`);
        selectCustomer.append(el);
    });
    modal.on('show.bs.modal', function () {
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
    });
    selectCustomer.on('change', validation);
    firstName.on('change paste keyup input blur', validation);
    roomNo.on('change paste keyup input blur', validation);
    inputTip.on('change paste keyup input blur', validation);
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);

        let pay = Payment({
            order_notes: txAreaNote.val(),
            //folio_id: data.folio_id, //REMARK until integrate to FO
            folio_id: 0,
            payment_type_id: 16,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            tip_amount: inputTip.data('value'),
            change_amount: 0
        });
        SQL('insert into pos_room_charge_detail set ?', {
            payment_id: pay.response.insertId,
            room_no: roomNo.val(),
            guest_first_name: firstName.val(),
            guest_last_name: lastName.val(),
            created_by: App.user.id
        });
        goHome(pay);
    });
};
let houseUsePayment = function () {
    if (!roleDetector('houseuse', 'houseuseafterpayment')) {
        El.btnPayHouseUse.hide();
        return;
    }
    let modal = El.modalHouseUse;
    let inputTip = modal.find('#add-tip');
    let selectHouseUse = modal.find('#house-use');
    let lblPeriod = modal.find('#period');
    let lblHouseUseInfo = modal.find('#house-use-info');
    let lblCostCenter = modal.find('#cost-center');
    let lblMaxSpent = modal.find('#max-spent');
    let lblCurrentUsed = modal.find('#current-used');
    let lblCurrentBalance = modal.find('#current-balance');
    let txAreaNote = modal.find('#note');
    let btnSubmit = modal.find('#submit');
    let validation = function () {
        let val1 = selectHouseUse.val();
        let val2 = inputTip.data('value') >= 0;
        data = houseUseList.data.filter(function (e) {
            return e.house_use_id == val1 ? 1 : 0;
        })[0] || {};
        data.current_transc_amount = data.current_transc_amount || 0;
        data.max_spent_monthly = data.max_spent_monthly || 0;
        data.spent = data.max_spent_monthly - data.current_transc_amount;
        house_use_id = (data.folio_id);
        lblPeriod.html(data.period || `/* NO PERIOD! */`);
        lblCostCenter.html(data.cost_center);
        lblHouseUseInfo.html(data.house_use);
        lblMaxSpent.html(rupiahJS(data.max_spent_monthly));
        lblCurrentUsed.html(rupiahJS(data.current_transc_amount));
        lblCurrentBalance.html(rupiahJS(data.spent));

        btnSubmit.prop('disabled', true);
        if (val1 && 1) {
            lblPeriod.css('color', 'black');
            if ((data.spent > 0) && (data.spent - parseFloat(grandtotal) >= 0) && val2) {
                btnSubmit.prop('disabled', false);
            }
        } else {
            lblPeriod.css('color', 'red');
        }
    }
    //
    El.btnPayHouseUse.show();
    //
    let total, discount, service, tax, grandtotal, house_use_id, data;
    let houseUseList = SQL(`
        select
            a.*, b.current_transc_amount, b.house_use,
            a.id house_use_id, b.period, b.cost_center,
            c.name pos_cost_center_name
        from mst_house_use a
        left join v_house_use_spent_monthly b on a.id = b.house_use_id and b.period = DATE_FORMAT(NOW(), '%Y%m')
        left join mst_pos_cost_center c on c.id = a.pos_cost_center_id
        where a.status = 1
    `);
    selectHouseUse.html('<option value="">- Choose -</option>');
    houseUseList.data.forEach(function (e) {
        HouseUse[e.house_use_id] = e;
        let el = $(`<option value="${e.house_use_id}">[${e.code}] - ${e.pos_cost_center_name} / ${e.name}</option>`);
        selectHouseUse.append(el);
    });
    modal.on('show.bs.modal', function () {
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
        inputTip.data('value', 0);
    });
    selectHouseUse.on('change', validation);
    inputTip.on('change paste keyup input blur', validation);
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            order_notes: txAreaNote.val(),
            house_use_id: data.house_use_id,
            payment_type_id: 17,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            tip_amount: inputTip.data('value'),
            change_amount: 0
        });
        goHome(pay);
    });
};
let voucherPayment = function () {
    if (!roleDetector('voucher', 'voucherafterpayment')) {
        El.btnPayVoucher.hide();
        return;
    }
    let modal = El.modalVoucher;
    let inputTip = modal.find('#add-tip');
    let btnSubmit = modal.find('#submit');
    let lblChange = modal.find('#change');
    let inputCode = modal.find('#code');
    let inputAmount = modal.find('#amount');
    let total, discount, service, tax, grandtotal, change;
    let validation = function () {
        let code = inputCode.val();
        let value = inputAmount.data('value');
        let val3 = inputTip.data('value') >= 0;
        change = parseFloat(value) - parseFloat(grandtotal) - inputTip.data('value');
        if (change>0) change=0;
        if (code && (change >= 0) && val3) {
            btnSubmit.prop('disabled', false)
            lblChange.html(rupiahJS(change));
        } else {
            btnSubmit.prop('disabled', true)
            lblChange.html(rupiahJS(0));
        }
    }
    //
    El.btnPayVoucher.show();
    modal.on('show.bs.modal', function () {
        btnSubmit.prop('disabled', true);
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
        change = 0;
        inputTip.data('value', 0);
    });
    inputTip.on('change paste keyup input blur', validation);
    inputCode.on('change paste keyup input blur', validation);
    inputAmount.on('change paste keyup input blur', validation);
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: 4,
            grandtotal: grandtotal,
            payment_amount: inputAmount.data('value'),
            tip_amount: inputTip.data('value'),
            change_amount: change,
            card_no: inputCode.val()
        });
        goHome(pay);
    });
};
let cityLedgerPayment = function () {
    if (!roleDetector('cityledger', 'cityledgerafterpayment')) {
        El.btnPayCityLedger.hide();
        return;
    }
    let modal = El.modalCityLedger;
    let inputTip = modal.find('#add-tip');
    let clName = modal.find('#cl-name');
    let selectCityLedger = modal.find('#city-ledger');
    let lblType = modal.find('#type');
    let lblName = modal.find('#name');
    let lblCode = modal.find('#code');
    let lblCPName = modal.find('#cpname');
    let lblCPNumb = modal.find('#cpnumber');
    let lblAddr = modal.find('#address');
    let lblBList = modal.find('#black-list');
    let lblCredit = modal.find('#credit');
    let lblAlert = modal.find('#alert');
    let lblNotes = modal.find('#notes');
    let lblSaldo = modal.find('#deposit-saldo');
    let lblBalance = modal.find('#deposit-change');
    let btnSubmit = modal.find('#submit');
    let validation = function () {
        let val1 = selectCityLedger.val();
        let val2 = inputTip.data('value') >= 0;
        let val3 = clName.val();
        if (val3.length>0) {
            btnSubmit.prop('disabled', false);
        }
        /*if (val1) {
            let data = CityLedger[val1];
            let addr = [data.address, data.city_name].join(', ');
            let n = [data.name, data.short_name].join(' / ');
            change = data.deposit_balance - grandtotal - inputTip.data('value');
            lblType.html(data.company_type || '-');
            lblName.html(n || '-');
            lblCode.html(data.code || '-');
            lblCPName.html(data.contact_person_name || '-');
            lblCPNumb.html(data.contact_person_phone || '-');
            lblAddr.html(addr || '-');
            lblBList.html(data.is_black_listed || '-');
            lblCredit.html(data.is_credit || '-');
            lblAlert.html(data.alert || '-');
            lblNotes.html(data.notes || '-');
            lblSaldo.html(rupiahJS(data.deposit_balance || '0'));
            lblBalance.html(rupiahJS(change));
            //if (change >= 0) btnSubmit.prop('disabled', false); //avoid this, let user make decision self
            if (val2) btnSubmit.prop('disabled', false);
        } else {
            lblType.html('');
            lblName.html('');
            lblCode.html('');
            lblCPName.html('');
            lblCPNumb.html('');
            lblAddr.html('');
            lblBList.html('');
            lblCredit.html('');
            lblAlert.html('');
            lblNotes.html('');
            lblSaldo.html('');
            lblBalance.html('');
        }*/
    };
    //
    El.btnPayCityLedger.show();
    //
    let total, discount, service, tax, grandtotal, change;
    let cityLedgerList = SQL(`
        select a.id, a.code, a.short_name, a.name, a.description,
            a.address, a.city_id, c.name city_name,
            a.contact_person_name, a.contact_person_phone,
            a.is_credit, a.is_black_listed, a.notes, a.alert,
            a.status, a.company_type_id, b.name company_type,
            d.deposit_amount, d.applied_amount,
            d.used_currency_id, (d.deposit_amount-d.applied_amount) deposit_balance,
            d.id deposit_id
        from mst_cust_company a
        left join ref_customer_type b on b.id = a.company_type_id
        left join ref_kabupaten c on c.id = a.city_id
        left join acc_ar_deposit d on d.customer_id = a.id
        where a.status = '1'
    `);
    selectCityLedger.html('<option value="">- Choose -</option>');
    cityLedgerList.data.forEach(function (e) {
        CityLedger[e.id] = e;
        let el = $(`<option value="${e.id}">[${e.code}] - ${e.name} / ${e.short_name}</option>`);
        selectCityLedger.append(el);
    });
    modal.on('show.bs.modal', function () {
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
        change = 0;
        btnSubmit.prop('disabled', true);
        inputTip.data('value', 0);
        lblType.html('');
        lblName.html('');
        lblCode.html('');
        lblCPName.html('');
        lblCPNumb.html('');
        lblAddr.html('');
        lblBList.html('');
        lblCredit.html('');
        lblAlert.html('');
        lblNotes.html('');
        lblSaldo.html('');
        lblBalance.html('');
    });
    selectCityLedger.on('change', validation);
    inputTip.on('change paste keyup input blur', validation);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: 2,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            tip_amount: inputTip.data('value'),
            change_amount: 0
        });
        SQL('insert into pos_city_ledger_detail set ?', {
            payment_id: pay.response.insertId,
            //room_no: roomNo.val(),
            company_name: clName.val(),
            created_by: App.user.id
        });
        //
        let val = selectCityLedger.val();
        let data = CityLedger[val];
        let getDate = SQL('select NOW() now');
        let date = new Date(getDate.data[0].now);
        let yyyymmdd = [
            date.getFullYear(),
            date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1,
            date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
        ];
        let getCode = SQL(`select next_document_no('AR', '${yyyymmdd.slice(0,2).join('/')}') as code`);
        //REMARK FO Integration
        /*
        let base_deposit = data.deposit_amount;
        let deposit_amount = change >= 0 ? grandtotal : data.deposit_balance;
        let total_amount = grandtotal;
        let total_due_amount = total_amount - deposit_amount;
        let current_due_amount = total_due_amount;
        */
        let base_deposit = 0;
        let deposit_amount = 0;
        let total_amount = grandtotal;
        let total_due_amount = 0;
        let current_due_amount = total_due_amount;
        let created_by = App.user.id;
        //let {id, deposit_id, applied_amount} = data;
        let arInvoice = SQL(`INSERT INTO acc_ar_invoice SET ?`, {
            code : getCode.data[0].code,
            status : '0',
            open_date : yyyymmdd.join('-'),
            //customer_id : id,
            customer_id : 0,
            total_amount, deposit_amount, total_due_amount, current_due_amount,
            created_by
        })
        /*let arDepLineItem = SQL(`INSERT INTO acc_ar_deposit_line_item SET ?`, {
            deposit_id, invoice_id: arInvoice.data.insertId, created_by
        });
        let updateArDep = SQL(`UPDATE acc_ar_deposit SET applied_amount = ?, modified_date = ?, modified_by = ? WHERE id = ?`, [
            applied_amount + deposit_amount > base_deposit ? base_deposit : applied_amount + deposit_amount,
            yyyymmdd.join('-'),
            created_by,
            deposit_id
        ])*/
        goHome(pay);
    });
};
let noPostPayment = function () {
    if (!roleDetector('nopost', 'nopostafterpayment')) {
        El.btnPayNoPost.hide();
        return;
    }
    let modal = El.modalNoPost;
    let txAreaNote = modal.find('#note');
    let btnSubmit = modal.find('#submit');
    //
    El.btnPayNoPost.show();
    //
    let total, discount, service, tax, grandtotal;
    modal.on('show.bs.modal', function () {
        total = getSummary('total').value;
        discount = getSummary('discount').value;
        service = getSummary('servicecharge').value;
        tax = getSummary('tax').value;
        grandtotal = getSummary('grandtotal').value;
    });
    txAreaNote.on('change paste keyup input blur', function () {
        if (txAreaNote.val()) {
            btnSubmit.prop('disabled', false);
        }
    });
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            status: 4,
            order_notes: txAreaNote.val()
        });
        goHome(pay);
    });
};
let multiPayment = function () {
    if (!roleDetector('multipayment', 'multipaymentafterpayment')) {
        El.btnMultiPayment.hide();
        return;
    }
    let m = El.modalMultiPayment;
    let divInfo = m.find('div#info');
    let close = m.find('#close');
    let next = m.find('#submit');
    let inputCounter = m.find('#mode-counter');
    let noState = m.find('#no-state');
    let state = m.find('#state');
    let modeLabel0 = m.find('#mode-label-0');
    let modeLabel1 = m.find('#mode-label-1');
    let balance = m.find('#balance');
    let recordList = $('<div>');
    //
    let count = 0;
    let paymentState = m.find('#payment-state');
    let recordPayment = function (i, value) {
        return $(`
        <div class="row">
            <div class="col-sm-6">
                <label>Payment #${i}</label>
            </div>
            <div class="col-sm-6 text-right">
                <label style="margin-right: 13px;">
                    ${rupiahJS(value)}
                </label>
            </div>
        </div>
    `);
    };
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
                                    <option value="voucher">Voucher</option>
                                    <option value="cityledger">City Ledger</option>
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
            } else if (el.val() == 'voucher') {
                payDialog.append(payWithVoucher(`split-${i}-voucher`));
            } else if (el.val() == 'cityledger') {
                payDialog.append(payWithCityLedger(`split-${i}-cityledger`));
            }
        });
        if (!roleDetector('cash', 'cashafterpayment')) pay.find('select option[value="cash"]').remove();
        if (!roleDetector('card', 'cardafterpayment')) pay.find('select option[value="card"]').remove();
        if (!roleDetector('chargeroom', 'chargeroomafterpayment')) pay.find('select option[value="charge2room"]').remove();
        if (!roleDetector('houseuse', 'houseuseafterpayment')) pay.find('select option[value="houseuse"]').remove();
        return pay;
    };
    let payWithCash = function (id) {
        let limit = parseInt(inputCounter.val());
        let pay = $(`
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Cash amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-paywith" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <span for="usr" style="font-style: italic">
                        <input type="checkbox" id="${id}-set-paid"> Make this cash amount as paid amount
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Paid amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Change</span>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-change" class="pull-right" style="margin-right: 13px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        `);
        let inputTip = pay.find(`#${id}-add-tip`);
        let change = pay.find(`#${id}-change`);
        let paywith = pay.find(`#${id}-paywith`);
        let setPaid = pay.find(`#${id}-set-paid`);
        let amount = pay.find(`#${id}-amount`);
        let validation = function () {
            let id = $(this).attr('id');
            let paywithVal = parseFloat(paywith.data('value'));
            let amountVal = parseFloat(amount.data('value'));
            let tipVal = parseFloat(inputTip.data('value'));
            let changeAmount = paywithVal - amountVal - tipVal;
            if (id.match(/split\-[0-9]\-cash\-set\-paid/g) && setPaid.is(":checked")) {
                amount.data('value', paywithVal);
                amount.data('display', rupiahJS(paywithVal));
                amount.val(rupiahJS(paywithVal));
                amountVal = paywithVal;
            }
            if (id.match(/split\-[0-9]\-cash\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
            }
            if (id.match(/split\-[0-9]\-cash\-amount/g)) {
                amount.data('value', amountVal);
                amount.data('display', rupiahJS(amountVal));
                amount.val(rupiahJS(amountVal));
            }
            if (id.match(/split\-[0-9]\-cash\-paywith/g)) {
                paywith.data('value', paywithVal);
                paywith.data('display', rupiahJS(paywithVal));
                paywith.val(rupiahJS(paywithVal));
            }
            //
            paywithVal = parseFloat(paywith.data('value'));
            amountVal = parseFloat(amount.data('value'));
            tipVal = parseFloat(inputTip.data('value')) || 0;
            changeAmount = paywithVal - amountVal - tipVal;
            next.disable();
            setPaid.prop('checked', amountVal != paywithVal ? 0 : 1)
            if ((amountVal > 0) && (paywithVal >= amountVal) && (changeAmount >= 0) && (tipVal >= 0)) {
                if (limit == count) {
                    if (amountVal >= balance.val()) {
                        change.attr('val', changeAmount);
                        change.html(rupiahJS(changeAmount))
                        next.enable();
                    }
                } else {
                    change.attr('val', changeAmount);
                    change.html(rupiahJS(changeAmount))
                    next.enable();
                }
            } else {
                change.attr('val', 0);
                change.html(rupiahJS(0))
            }
        };
        if (limit == count) setPaid.parent().hide();
        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        paywith.keyboard(getOpts({layout: 'numpad'}, 1));
        inputTip.css('text-align', 'end');
        paywith.css('text-align', 'end');
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.css('text-align', 'end');
        setPaid.on('change', validation)
        inputTip.on('change paste keyup input blur', validation);
        amount.on('change paste keyup input blur', validation);
        paywith.on('change paste keyup input blur', validation);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let payWithCard = function (id) {
        let limit = parseInt(inputCounter.val());
        let options1 = El.modalCard.find('#bank-type').html();
        let options2 = El.modalCard.find('#bank-account').html();
        let pay = $(`
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <span>Type</span>
                        <select class="form-control" id="${id}-select">
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <span>EDC</span>
                        <select class="form-control" id="${id}-card_type"></select>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <span>Card Type</span>
                        <select class="form-control" id="${id}-cc_type"></select>
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
                        <span for="usr">Number:</span>
                        <input id="${id}-cardno" type="number" class="form-control">
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <span for="usr">Name:</span>
                        <input id="${id}-customer" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Pay Amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
        `);
        let inputTip = pay.find(`#${id}-add-tip`);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        let selectEDC = pay.find(`#${id}-card_type`);
        let selectCCType = pay.find(`#${id}-cc_type`);
        let cardNo = pay.find(`#${id}-cardno`);
        let customer = pay.find(`#${id}-customer`);
        let swiper = pay.find(`#${id}-swiper`);
        let validate = function () {
            let typeVal = select.val();
            let customerVal = customer.val();
            let cardNoVal = cardNo.val();
            let selectEDCVal = parseInt(selectEDC.val());
            let selectCCTypeVal = parseInt(selectCCType.val());
            let amountVal = parseFloat(amount.data('value'));
            let tipVal = parseFloat(inputTip.data('value')) || 0;
            next.disable();
            if ($(this).attr('id').match(/split\-[0-9]\-card\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
                tipVal = parseFloat(inputTip.data('value')) || 0
            }
            if (typeVal == 'credit') {
                if (customerVal && cardNoVal && selectEDCVal && selectCCTypeVal && (amountVal > 0) && (tipVal >= 0)) {
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
                if (cardNoVal && selectEDCVal && selectCCTypeVal && (amountVal > 0) && (tipVal >= 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            }
        };
        selectEDC.html(options1);
        selectCCType.html(options2);
        //
        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        inputTip.css('text-align', 'end');
        amount.css('text-align', 'end');
        amount.on('change paste keyup input blur', function () {
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
            let selectEDCVal = parseInt(selectEDC.val());
            let amountVal = parseInt(el.data('value'));
            next.disable();
            if (typeVal == 'credit') {
                customer.parent().show();
                if (customerVal && cardNoVal && selectEDCVal && (amountVal > 0)) {
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
                if (cardNoVal && selectEDCVal && (amountVal > 0)) {
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
        cardNo.keyboard(getOpts({layout: 'numpad'}));
        customer.keyboard(getOpts({layout: 'qwerty'}));
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
        selectEDC.on('change', validate)
        selectCCType.on('change', validate)
        cardNo.on('change paste keyup input blur', validate);
        customer.on('change paste keyup input blur', validate);
        inputTip.on('change paste keyup input blur', validate);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let payWithCharge2Room = function (id) {
        let limit = parseInt(inputCounter.val());
        let options = El.modalCharge2Room.find('#customer').html();
        //REMARK until integrate to FO
        /*let pay = $(`
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <span>Customer</span>
                        <select class="form-control" id="${id}-select">
                            ${options}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span>Check In</span>
                    <span> : </span>
                    <span id="${id}-check_in_date"></span>
                </div>
                <div class="col-sm-6">
                    <span>Departure</span>
                    <span> : </span>
                    <span id="${id}-departure_date"></span>
                </div>
                <div class="col-sm-6">
                    <span>Cash Bases</span>
                    <span> : </span>
                    <span id="${id}-is_cash_bases"></span>
                </div>
                <div class="col-sm-6">
                    <span>Room Only</span>
                    <span> : </span>
                    <span id="${id}-is_room_only"></span>
                </div>
                <div class="col-sm-6">
                    <span>Reservation Type</span>
                    <span> : </span>
                    <span id="${id}-reservation_type"></span>
                </div>
                <div class="col-sm-6">
                    <span>Room No</span>
                    <span> : </span>
                    <span id="${id}-room_no"></span>
                </div>
                <div class="col-sm-6">
                    <span>Room Rate</span>
                    <span> : </span>
                    <span id="${id}-room_rate_code"></span>
                </div>
                <div class="col-sm-6">
                    <span>Room Type</span>
                    <span> : </span>
                    <span id="${id}-room_type"></span>
                </div>
                <div class="col-sm-6">
                    <span>VIP Type</span>
                    <span> : </span>
                    <span id="${id}-vip_type"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Pay Amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
        `);*/
        let pay = $(`
            <div class="row"><div class="col-lg-12 "><b>Customer Info:</b></div></div>
            <div class="row">

                <div class="col-lg-3">
                    <span>First Name *</span>
                </div>
                <div class="col-lg-3 ">
                    <input type="text" class="form-control" id="${id}-ctr-first-name">
                </div>
                <div class="col-lg-3">
                    <span>Last Name</span>
                </div>
                <div class="col-lg-3 ">
                    <input type="text" class="form-control" id="${id}-ctr-last-name">
                </div>

            </div>
            <div class="row" style="margin-top:5px">
                <div class="col-lg-3">
                    <span>Room Number *</span>
                </div>
                <div class="col-lg-3 ">
                    <input type="number" class="form-control" id="${id}-ctr-room-number">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Pay Amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
        `);
        let inputTip = pay.find(`#${id}-add-tip`);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        let firstName = pay.find(`#${id}-ctr-first-name`);
        let lastName = pay.find(`#${id}-ctr-last-name`);
        let roomNo = pay.find(`#${id}-ctr-room-number`);
        let validate = function () {
            let tipVal = parseFloat(inputTip.data('value'));
            let amountVal = parseFloat(amount.data('value'));
            let selectVal = select.val();
            //REMARK until integrate to FO
            /*
            let data = Charge2Room.current = Charge2Room[selectVal];
            data = data || {};
            pay.find(`#${id}-check_in_date`).html(data.check_in_date);
            pay.find(`#${id}-departure_date`).html(data.departure_date);
            pay.find(`#${id}-is_cash_bases`).html(data.is_cash_bases);
            pay.find(`#${id}-is_room_only`).html(data.is_room_only);
            pay.find(`#${id}-reservation_type`).html(data.reservation_type);
            pay.find(`#${id}-room_no`).html(data.room_no);
            pay.find(`#${id}-room_rate_code`).html(data.room_rate_code + '/' + data.room_rate_name);
            pay.find(`#${id}-room_type`).html(data.room_type + '/' + data.room_type_name);
            pay.find(`#${id}-vip_type`).html(data.vip_type);
            */
            //
            if ($(this).attr('id').match(/split\-[0-9]\-charge\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
                tipVal = parseFloat(inputTip.data('value')) || 0
            }
            if ($(this).attr('id').match(/split\-[0-9]\-charge\-amount/g)) {
                amount.data('value', amountVal);
                amount.data('display', rupiahJS(amountVal));
                amount.val(rupiahJS(amountVal));
                amountVal = parseFloat(amount.data('value')) || 0
            }
            next.disable();
            //if (selectVal && (data.is_cash_bases.toLowerCase() === 'n')) {
            if (firstName && roomNo) {
                if ((amountVal > 0) && (amountVal <= balance.val()) && (tipVal >= 0)) {
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
        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        firstName.keyboard(getOpts({layout: 'qwerty'}));
        lastName.keyboard(getOpts({layout: 'qwerty'}));
        roomNo.keyboard(getOpts({layout: 'numpad'}, 1));
        inputTip.css('text-align', 'end');
        amount.css('text-align', 'end');
        amount.on('change paste keyup input blur', validate);
        inputTip.on('change paste keyup input blur', validate);
        select.on('change', validate);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let payWithHouseUse = function (id) {
        let limit = parseInt(inputCounter.val());
        let options = El.modalHouseUse.find('#house-use').html();
        let pay = $(`
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <span>House use</span>
                        <select class="form-control" id="${id}-select">
                            ${options}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span>Period</span>
                    <span> : </span>
                    <span id="${id}-period"></span>
                </div>
                <div class="col-sm-12">
                    <span>House use</span>
                    <span> : </span>
                    <span id="${id}-house_use"></span>
                </div>
                <div class="col-sm-12">
                    <span>Cost Center</span>
                    <span> : </span>
                    <span id="${id}-cost_center"></span>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Monthly Spent</span>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-max_spent_monthly" class="pull-right" style="margin-right: 10px;"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Current Transaction Amount</span>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-current_transc_amount" class="pull-right" style="margin-right: 10px;"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Balance</span>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-balance" class="pull-right" style="margin-right: 10px;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Pay Amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
        `);
        //
        let inputTip = pay.find(`#${id}-add-tip`);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        let validate = function () {
            let selectVal = select.val();
            let amountVal = parseFloat(amount.data('value')) || 0;
            let tipVal = parseFloat(inputTip.data('value')) || 0;
            let data = HouseUse.current = HouseUse[selectVal];
            data = data || {};
            //
            data.current_transc_amount = data.current_transc_amount || 0;
            data.max_spent_monthly = data.max_spent_monthly || 0;
            data.spent = data.max_spent_monthly - data.current_transc_amount;
            pay.find(`#${id}-cost_center`).html(data.cost_center);
            pay.find(`#${id}-house_use`).html(data.house_use);
            pay.find(`#${id}-current_transc_amount`).html(rupiahJS(data.current_transc_amount));
            pay.find(`#${id}-max_spent_monthly`).html(rupiahJS(data.max_spent_monthly));
            pay.find(`#${id}-balance`).html(rupiahJS(data.spent));
            pay.find(`#${id}-period`).html(data.period || `/* NO PERIOD! */`);
            //
            if ($(this).attr('id').match(/split\-[0-9]\-house\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
                tipVal = parseFloat(inputTip.data('value')) || 0
            }
            if ($(this).attr('id').match(/split\-[0-9]\-house\-amount/g)) {
                amount.data('value', amountVal);
                amount.data('display', rupiahJS(amountVal));
                amount.val(rupiahJS(amountVal));
                amountVal = parseFloat(amount.data('value')) || 0
            }
            //
            let spent = parseFloat(data.max_spent_monthly) - parseFloat(data.current_transc_amount);
            let total = amountVal - tipVal;

            next.disable();
            if (selectVal && data.period) {
                pay.find(`#${id}-period`).css('color', 'black');
                if ((data.spent >= total) && (amountVal > 0) && (amountVal <= balance.val()) && (tipVal >= 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            } else {
                pay.find(`#${id}-period`).css('color', 'red');
            }
        }

        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        inputTip.css('text-align', 'end');
        amount.css('text-align', 'end');
        amount.on('change paste keyup input blur', validate);
        inputTip.on('change paste keyup input blur', validate);
        select.on('change', validate);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let payWithVoucher = function (id) {
        let limit = parseInt(inputCounter.val());
        let pay = $(`
            <div class="row">
                <div class="col-lg-6">
                    <span>Voucher Code</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="text" class="form-control" id="${id}-code">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Voucher Amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-amount">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Change</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span id="${id}-change" style="margin-right: 13px;"></span>
                </div>
            </div>


        `);
        let inputTip = pay.find(`#${id}-add-tip`);
        let change = pay.find(`#${id}-change`);
        let code = pay.find(`#${id}-code`);
        let amount = pay.find(`#${id}-amount`);
        let validation = function () {
            let id = $(this).attr('id');
            let vcrCode = code.val();
            let amountVal = parseFloat(amount.data('value'));
            let tipVal = parseFloat(inputTip.data('value'));
            let changeAmount = amountVal - tipVal;
            if (id.match(/split\-[0-9]\-cash\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
            }
            if (id.match(/split\-[0-9]\-cash\-amount/g)) {
                amount.data('value', amountVal);
                amount.data('display', rupiahJS(amountVal));
                amount.val(rupiahJS(amountVal));
            }
            if (id.match(/split\-[0-9]\-cash\-paywith/g)) {
                paywith.data('value', paywithVal);
                paywith.data('display', rupiahJS(paywithVal));
                paywith.val(rupiahJS(paywithVal));
            }
            //

            next.disable();
            //if (selectVal && (data.is_cash_bases.toLowerCase() === 'n')) {
            if (vcrCode.length>0) {
                if ((amountVal > 0) && (amountVal <= balance.val()) && (tipVal >= 0)) {
                    if (limit == count) {
                        if (amountVal >= balance.val()) {
                            next.enable();
                        }
                    } else {
                        next.enable();
                    }
                }
            }

        };
        //if (limit == count) setPaid.parent().hide();
        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        inputTip.css('text-align', 'end');
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.css('text-align', 'end');
        code.keyboard(getOpts({layout: 'qwerty'}, 1));
        inputTip.on('change paste keyup input blur', validation);
        amount.on('change paste keyup input blur', validation);
        code.on('change paste keyup input blur', validation);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let payWithCityLedger = function (id) {
        let limit = parseInt(inputCounter.val());


        let pay = $(`
            <div class="row"><div class="col-lg-12 "><b>Company Info:</b></div></div>
            <div class="row">

                <div class="col-lg-3">
                    <span>Company Name *</span>
                </div>
                <div class="col-lg-3 ">
                    <input type="text" class="form-control" id="${id}-ctr-company-name">
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span for="usr">Pay Amount</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-amount" type="currency" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-6">
                    <span>Tip amount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <input type="currency" class="form-control" id="${id}-add-tip">
                </div>
            </div>
        `);
        let inputTip = pay.find(`#${id}-add-tip`);
        let amount = pay.find(`#${id}-amount`);
        let select = pay.find(`#${id}-select`);
        let companyName = pay.find(`#${id}-ctr-company-name`);
        let validate = function () {
            let tipVal = parseFloat(inputTip.data('value'));
            let amountVal = parseFloat(amount.data('value'));
            let selectVal = select.val();
            if ($(this).attr('id').match(/split\-[0-9]\-charge\-add\-tip/g)) {
                inputTip.data('value', tipVal);
                inputTip.data('display', rupiahJS(tipVal));
                inputTip.val(rupiahJS(tipVal));
                tipVal = parseFloat(inputTip.data('value')) || 0
            }
            if ($(this).attr('id').match(/split\-[0-9]\-charge\-amount/g)) {
                amount.data('value', amountVal);
                amount.data('display', rupiahJS(amountVal));
                amount.val(rupiahJS(amountVal));
                amountVal = parseFloat(amount.data('value')) || 0
            }
            next.disable();
            //if (selectVal && (data.is_cash_bases.toLowerCase() === 'n')) {
            if (companyName) {
                if ((amountVal > 0) && (amountVal <= balance.val()) && (tipVal >= 0)) {
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
        inputTip.keyboard(getOpts({layout: 'numpad'}, 1));
        amount.keyboard(getOpts({layout: 'numpad'}, 1));
        companyName.keyboard(getOpts({layout: 'qwerty'}));
        inputTip.css('text-align', 'end');
        amount.css('text-align', 'end');
        amount.on('change paste keyup input blur', validate);
        inputTip.on('change paste keyup input blur', validate);
        select.on('change', validate);
        inputTip.data('value', 0);
        //
        if (limit == count) {
            amount.attr('disabled', 1);
            amount.data('value', parseFloat(balance.val()));
            amount.data('display', rupiahJS(parseFloat(balance.val())));
            amount.val(rupiahJS(parseFloat(balance.val())));
        }
        return pay;
    };
    let grandtotal;
    //
    El.btnMultiPayment.show();
    //
    balance.val = function (value) {
        if (value) {
            balance.html(rupiahJS(value));
            $(this).attr('val', value);
        }
        let val = $(this).attr('val');
        return parseFloat(val);
    };
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
        close.disable();
        if (paymentState.is(':visible')) {
            let limit = parseInt(inputCounter.val());
            let bayar = parseFloat(paymentState.find('[id*="-amount"]').data('value'));
            let nextBalance = balance.val() - parseFloat(bayar);
            let an = paymentState.find('[id*="-mode"]').find('select').val();
            let d = {IS_MULTIPAY: true, MULTIPAY: count};
            if (an == 'cash') {
                d.payment_type_id = 11;
                d.payment_amount = paymentState.find('[id*="-cash-paywith"]').data('value');
                d.grandtotal = paymentState.find('[id*="-cash-amount"]').data('value');
                d.tip_amount = paymentState.find('[id*="-cash-add-tip"]').data('value')
                d.change_amount = paymentState.find('[id*="-cash-change"]').attr('val');
            } else if (an == 'card') {
                d.payment_type_id = paymentState.find('[id*="-card-card_type"]').val();
                d.card_type_id = paymentState.find('[id*="-card-cc_type"]').val();
                d.payment_amount = paymentState.find('[id*="-card-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.tip_amount = paymentState.find('[id*="-card-add-tip"]').data('value');
                d.change_amount = 0;
                d.card_no = paymentState.find('[id*="-cardno"]').val();
            } else if (an == 'charge2room') {
                d.payment_type_id = 16;
                d.payment_amount = paymentState.find('[id*="-charge-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.tip_amount = paymentState.find('[id*="-charge-add-tip"]').data('value');
                d.change_amount = 0;
                //d.folio_id = paymentState.find('[id*="-charge-select"]').val(); //REMARK integrate FO
                d.folio_id = 0;
            } else if (an == 'houseuse') {
                d.payment_type_id = 17;
                d.payment_amount = paymentState.find('[id*="-amount"]').data('value');
                d.grandtotal = d.payment_amount;
                d.tip_amount = paymentState.find('[id*="-house-add-tip"]').data('value');
                d.change_amount = 0;
                d.house_use_id = paymentState.find('[id*="-house-select"]').val();
            }

            if (nextBalance > 0) {
                next.disable();
                balance.val(nextBalance);
                balance.html(rupiahJS(nextBalance));
                recordList.append(recordPayment(count, bayar));

                console.info('Multi payment > saving start #' + count);
                let pay = Payment(d);
                if (pay.success) {
                    console.info('Multi payment > saving done #' + count);
                    //REMARK avail non FO
                    if (an == 'charge2room'){
                        SQL('insert into pos_room_charge_detail set ?', {
                            payment_id: pay.response.insertId,
                            room_no: paymentState.find('[id*="-ctr-room-number"]').data('value'),
                            guest_first_name: paymentState.find('[id*="-ctr-first-name"]').data('value'),
                            guest_last_name: paymentState.find('[id*="-ctr-last-name"]').data('value'),
                            created_by: App.user.id
                        });
                    }
                    paymentState.hide();
                    next.enable();
                    next.click();
                } else {
                    console.error('Multi payment > saving error #' + count);
                    alert(`Error occurrence, result : ${JSON.stringify(pay.response)}`);
                }
            } else if ((nextBalance <= 0) || (count == limit)) {
                next.disable();
                balance.val(nextBalance);
                balance.html(rupiahJS(nextBalance));
                recordList.append(recordPayment(count, bayar));
                d.MULTIPAY = 'done';
                console.info('Multi payment > saving & printing start #' + count);
                let pay = Payment(d);
                if (pay.success) {
                    console.info('Multi payment > saving & printing done #' + count);
                    console.info('Multi payment > finished!');
                    //REMARK avail non FO
                    if (an == 'charge2room'){
                        SQL('insert into pos_room_charge_detail set ?', {
                            payment_id: pay.response.insertId,
                            room_no: paymentState.find('[id*="-ctr-room-number"]').data('value'),
                            guest_first_name: paymentState.find('[id*="-ctr-first-name"]').data('value'),
                            guest_last_name: paymentState.find('[id*="-ctr-last-name"]').data('value'),
                            created_by: App.user.id
                        });
                    }
                    else if (an == 'cityledger'){
                        SQL('insert into pos_city_ledger_detail set ?', {
                            payment_id: pay.response.insertId,
                            company_name: paymentState.find('[id*="-ctr-company-name"]').data('value'),
                            created_by: App.user.id
                        });
                    }
                    paymentState.hide();
                    close.enable();
                    state.hide();
                    noState.hide();
                    goHome(pay);
                } else {
                    console.error('Multi payment > saving & printing error #' + count);
                    alert(`Error occurrence, result : ${JSON.stringify(res.result)}`);
                }
            }
        } else {
            count += 1;
            paymentState.hide();
            modeLabel0.html('Split Payment');
            modeLabel1.html(`${count} of ${inputCounter.val()}`);
            paymentState.show();
            paymentState.html('');
            paymentState.append(payment(count));
        }
    });
    inputCounter.on('change paste keyup input blur', function () {
        let by = inputCounter.val();
        next.disable();
        if (parseInt(by) > 0) next.enable();
    });
    //
    m.on('show.bs.modal', function () {
        grandtotal = getSummary('grandtotal').value
        balance.val(grandtotal);
        inputCounter.val('');
        recordList.html('');
        next.disable();
        m.find('.modal-dialog').removeClass('modal-lg');
        noState.show();
        state.hide();
        count = 0;
    });
    inputCounter.keyboard(getOpts({layout: 'numpad'}));
    recordList.insertBefore(balance.closest('.row'));
    m.modal({backdrop: 'static', keyboard: false});
    m.modal('hide');
};
let splitBill = function () {
    if (!roleDetector('splitbill', 'splitbillafterpayment')) {
        El.btnSplitBilling.hide();
        return;
    }
    let m = El.modalSplitBill;
    let next = m.find('#submit');
    let inputCounter = m.find('#mode-counter');
    let noState = m.find('#no-state');
    let state = m.find('#state');
    let allItems = [], allSheets = [], count = 0;
    //
    El.btnSplitBilling.show();
    //
    next.enable = function () {
        next.removeAttr('disabled');
    };
    next.disable = function () {
        next.attr('disabled', 1);
    };
    next.on('click', function () {
        next.disable();
        //
        let data = El.tableSheets.bootstrapTable('getData');
        let sheets = data.filter(function (e) {
            if (e.total > 0) return 1
            return 0;
        });
        let oId = orderIds.split(',')[0];
        let order = Order[oId];
        //
        SQL(`UPDATE pos_orders SET status=6 WHERE id=?`, parseInt(oId));
        sheets.forEach(function (sheet, i) {
            let init = SQL('INSERT pos_orders SET ?', {
                parent_id: parseInt(oId),
                table_id: order.table_id,
                code: order.code + '#' + (i + 1),
                transc_batch_id: App.posCashier.id,
                outlet_id: App.outlet.id,
                sub_total_amount: 0,
                discount_total_amount: 0,
                tax_total_amount: 0,
                due_amount: 0,
                segment_id: 1,
                status: 0,
                waiter_user_id: App.user.id,
                created_by: App.user.id
            });
            if (!init.error) {
                let lastId = init.data.insertId;
                let taxes = SQL(`
                    select b.*
                    from pos_outlet_tax a
                    left join mst_pos_taxes b on b.id = a.pos_tax_id
                    where outlet_id = ?
                `, App.outlet.id);
                if (!taxes.error) {
                    let result = [];
                    taxes.data.forEach(function (tax) {
                        let orderTax = SQL('insert into pos_order_taxes set ?', {
                            order_id: lastId,
                            tax_id: tax.id,
                            tax_percent: tax.tax_percent,
                            tax_amount: 0,
                            created_by: App.user.id
                        });
                        if (!orderTax.error) result.push(1);
                        else result.push(0);
                    });
                    if (result.indexOf(0) < 0) {
                        let qty = {};
                        let discount = {};
                        sheet.items_ = {};
                        sheet.order_id = lastId;
                        sheet.items.forEach(function (d) {
                            let k = d.line_item_id;
                            let disc = d.addDiscount;

                            sheet.items_[k] = {
                                id: d.outlet_menu_id,
                                menu_class_id: d.menu_class_id,
                                outlet_id: d.outlet_id,
                                menu_price: d.price_amount,
                                name: d.name,
                            };

                            if (disc.amount) {
                                discount[k] = discount[k] || {amount:0, percent:0};
                                if (disc.type == 'percent') {
                                    let amount = disc.percent / 100 * d.price_amount
                                    discount[k].percent = disc.percent;
                                    discount[k].amount += amount;
                                    discount[k].type = 'percent';
                                } else {
                                    discount[k].type = 'amount';
                                    discount[k].amount += disc.amount;
                                }
                            }

                            qty[k] = qty[k] || 0;
                            qty[k]++;
                        });

                        for (let k in sheet.items_) {
                            sheet.items_[k].qty = qty[k];
                            if (discount.hasOwnProperty(k)) {
                                sheet.items_[k].addDiscount = discount[k]
                            }
                            addOrderMenu(sheet.items_[k], qty[k], lastId);
                        }
                    }
                }
                let total = loadTotal(lastId);
                sheet.summary = getSummary(0, total);
            }
        });
        window.location.reload();
    });
    inputCounter.on('change paste keyup input blur', function () {
        let by = inputCounter.val();

        next.disable();
        if (parseFloat(by) > 1) {
            allSheets = [];
            for (let i = 0; i < by; i++) {
                allSheets.push({
                    index: i,
                    name: i + 1,
                    total: 0,
                    total_: rupiahJS(0),
                    items: []
                });
            }
            El.tableSheets.bootstrapTable('removeAll');
            El.tableSheets.bootstrapTable('resetView');
            El.tableSheets.bootstrapTable('load', allSheets);
        }
    });
    El.btnItem2Sheet.on('click', function () {
        let sheet = El.tableSheets.bootstrapTable('getSelections');
        let menuselection = El.tableItems.bootstrapTable('getSelections');
        let menus = menuselection.filter(function (e) {
            if (e.sheet) return 0;
            return 1;
        });
        if (sheet.length && menus.length) {
            let total = Object.keys(menus).reduce(function (previous, key) {
                menus[key].sheet = sheet[0].name;
                menus[key].sheetIdx = sheet[0].index;
                El.tableItems.bootstrapTable('updateRow', {index: menus[key].index, row: menus[key]});
                return previous + menus[key].price_amount;
            }, 0);
            let row = sheet[0];
            row.items = row.items.concat(menus);
            row.total += total;
            row.total_ = rupiahJS(row.total);
            El.tableSheets.bootstrapTable('updateRow', {index: row.index, row: row});
            //
            let menu2sheet = El.tableItems.bootstrapTable('getSelections').filter(function (e) {
                if (e.sheet) return 1
                return 0;
            });
            if (menu2sheet.length == El.tableItems.bootstrapTable('getData').length) {
                next.enable();
            }
        }
    });
    //
    m.on('show.bs.modal', function () {
        inputCounter.val('');
        next.disable();
        //
        allItems = [];
        OrderMenu.forEach(function (menu) {
            if (menu.type == '1') {
                let d = Object.assign({}, menu);
                let s = (d.order_qty - d.order_void);
                d.sheet = '';
                d.sheetIdx = '';
                if (s > 0) {
                    if (s > 1) {
                        for (let j = 0; j < s; j++) {
                            let dd = Object.assign({}, d);
                            dd.index = allItems.length;
                            allItems.push(dd);
                        }
                    } else {
                        d.index = allItems.length;
                        allItems.push(d);
                    }
                }
            }
        });
        El.tableSheets.bootstrapTable('load', allSheets);
        El.tableItems.bootstrapTable('load', allItems);
    });
    inputCounter.keyboard(getOpts({layout: 'numpad'}));
    m.modal({backdrop: 'static', keyboard: false});
    m.modal('hide');
    window.miscFn2 = function (value, row, index) {
        if (row.sheet) {
            return {
                disabled: true,
                checked: true
            }
        }
        return value;
    };
    window.miscFn3 = function () {
        return (
            `<a class="reset" href="#" title="Reset">
                <i class="glyphicon glyphicon-repeat text-info"></i>
            </a>`
        );
    };
    window.miscFn3Cfg = {
        'click .reset': function (e, value, row, index) {
            if (row.items.length) {
                row.items.forEach(function (item) {
                    item.sheet = '';
                    item.sheetIdx = '';
                    El.tableItems.bootstrapTable('updateRow', {index: item.index, row: item});
                });
                row.total= 0;
                row.total_= rupiahJS(0);
                row.items = [];
                next.disable();
                El.tableSheets.bootstrapTable('updateRow', {index: index, row: row});
            }
        }
    };
};
let saveOrderNote = function () {
    if (!roleDetector('ordernote', 'ordernoteafterpayment')) {
        El.btnOrderNote.hide();
        return;
    }
    let modal = El.modalAddNote;
    let txAreaNote = modal.find('#note');
    let btnSubmit = modal.find('#submit');
    //
    El.btnOrderNote.show();
    El.btnOrderNote.on('click', function () {
        let orderNote = SQL('select order_notes from pos_orders where id=?', orderIds.split(',')[0]);
        txAreaNote.val(orderNote.data[0].order_notes);
    });
    txAreaNote.on('change paste keyup input blur', function () {
        if (txAreaNote.val()) {
            btnSubmit.prop('disabled', false);
        }
    });
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let updateOrderNote = SQL('update pos_orders set order_notes=? where id=?', [
            txAreaNote.val(), orderIds.split(',')[0]
        ]);
        if (!updateOrderNote.error) {
            modal.modal('hide')
        }
    });
};
let manualPrint = function () {
    if (!roleDetector('printorder', 'printorderafterpayment')) {
        El.btnPrintOrder.hide();
        return;
    }
    let modal = El.modalPrintOrder;
    let btnPrint = modal.find('#print');
    let btnRePrint = modal.find('#reprint');
    let btnManualPrint = modal.find('#manualprint');
    let btnTogglePrint = modal.find('#printtoogle');
    let divDefaultPrint = modal.find('#printContentDefault');
    let divManualPrint = modal.find('#printContentManual');
    let tbodyDefaultPrint = divDefaultPrint.find('table tbody');
    let ulCheckListBox = divManualPrint.find('#check-list-box');
    let ngAjax = function (data, callback) {
        $.ajax({
            method: 'GET',
            url: '/printKitchen',
            data: data,
            complete: function (xhr, is) {
                if (is == 'success') {
                    if (xhr.responseJSON) {
                        if (!xhr.responseJSON.error) callback(xhr.responseJSON.message);
                        else console.error(xhr.responseJSON.message);
                    } else console.error(xhr.response);
                } else console.error('Server down!');
            }
        })
    };
    //
    El.btnPrintOrder.show();
    //
    modal.on('show.bs.modal', function () {
        btnPrint.show();
        btnRePrint.show();
        btnManualPrint.hide();
        btnTogglePrint.html('Manual Print');
        divManualPrint.hide();
        divDefaultPrint.show();
        btnPrint.removeAttr('disabled');
        btnRePrint.removeAttr('disabled');
        btnManualPrint.removeAttr('disabled');
        //
        tbodyDefaultPrint.html('');
        ulCheckListBox.html('');
        let orders = SQL(`
            select
                c.name menu,b.order_qty,f.name printer
            from pos_orders a,pos_orders_line_item b,user d,mst_outlet e,inv_outlet_menus c
            left join mst_kitchen_section f on c.print_kitchen_section_id=f.id
            where a.id=b.order_id
            and b.outlet_menu_id=c.id
            and a.waiter_user_id=d.id
            and a.outlet_id=e.id
            and order_id in (${orderIds});
        `);
        orders.data.forEach(function (e) {
            tbodyDefaultPrint.append(`
                <tr>
                    <td>${e.order_qty}</td>
                    <td>${e.menu}</td>
                    <td>${e.printer || '?'}</td>
                </tr>
            `)
        })
        let printers = SQL(`select id, kitchen_id, code, name from mst_kitchen_section`);
        printers.data.forEach(function (e) {
            let li = $(`
                <li id="printer${e.id}" class="list-group-item" data-color="info" name="${e.name}" code="${e.code}">
                    <span style="margin-left: 10px">${e.code.toUpperCase()}</span>
                    <span class='badge badge-default badge-pill'>${e.name}</span>
                </li>
            `);
            li.data('!', e);
            ulCheckListBox.append(li)

        });
        initCheckListBoxes();
    });
    btnTogglePrint.on('click', function () {
        if (btnTogglePrint.attr('state') == 'default') {
            btnManualPrint.show();
            btnPrint.hide();
            btnRePrint.hide();
            btnTogglePrint.html('Back');
            btnTogglePrint.attr('state', 'manual');
            divManualPrint.show();
            divDefaultPrint.hide();
        } else {
            btnPrint.show();
            btnRePrint.show();
            btnManualPrint.hide();
            btnTogglePrint.html('Manual Print');
            btnTogglePrint.attr('state', 'default');
            divManualPrint.hide();
            divDefaultPrint.show();
        }
    })
    btnPrint.on('click', function () {
        btnPrint.attr('disabled', 1);
        ngAjax({orderId: orderIds.split(',')}, function () {
            modal.modal('hide');
        })
    });
    btnRePrint.on('click', function () {
        btnRePrint.attr('disabled', 1);
        ngAjax({reprint: 1, orderId: orderIds.split(',')}, function () {
            modal.modal('hide');
        });
    });
    btnManualPrint.on('click', function () {
        let active = modal.find('#check-list-box li.active');
        if (active.length) {
            btnManualPrint.attr('disabled', 1);
        }
        active.each(function (idx, li) {
            let list = ulCheckListBox.find('li');
            let id = list.attr('id').replace('printer-', '');
            let code = list.attr('code');
            let printer = list.attr('name');
            ngAjax({orderId: orderIds.split(','), printer}, function () {
                modal.modal('hide');
            });
        });
    });
};
let openMenu = function () {
    if (!roleDetector('openmenu', 'FORCE')) {
        El.btnOpenMenu.hide();
        return;
    }
    let m = El.modalOpenMenu;
    let slctMealTime = m.find('#open-meal-time');
    let slctMenuCls = m.find('#open-menu-class');
    let slctMenuSubCls = m.find('#open-menu-sub-class');
    let slctPrintToKitchen = m.find('#open-print-to-kitchen');
    let inputMenuName = m.find('#open-menu-name');
    let inputMenuPrice = m.find('#open-menu-price');
    let inputMenuQty = m.find('#open-menu-qty');
    let btnSubmit = m.find('#submit');
    let validation = function () {
        let val1 = slctMealTime.val();
        let val2 = slctMenuCls.val();
        let val3 = slctMenuSubCls.val();
        let val4 = inputMenuName.val();
        let val5 = slctPrintToKitchen.val();
        let val6 = inputMenuPrice.data('value');
        let val7 = inputMenuQty.data('value');
        btnSubmit.prop('disabled', true);
        if (val1 && val2 && val3 && val4 && val5 && parseFloat(val6) > 0 && parseFloat(val7) > 0) {
            btnSubmit.prop('disabled', false);
        }
    };
    //
    El.btnOpenMenu.show();
    let mealTime = SQL(`select * from ref_meal_time`);
    slctMealTime.html('<option value="">- Choose -</option>');
    if (!mealTime.error) {
        mealTime.data.forEach(function (e) {
            slctMealTime.append(`
                <option value="${e.id}">${e.code} - ${e.name}</option>
            `)
        })
    }
    //
    let printers = SQL(`
        select
            a.id, a.kitchen_id, a.code, a.name, b.name kitchen_name
        from mst_kitchen_section a
        left join mst_kitchen b on b.id = a.kitchen_id
        where a.status = 1
    `);
    slctPrintToKitchen.html('<option value="">- Choose -</option>');
    if (!printers.error) {
        printers.data.forEach(function (e) {
            slctPrintToKitchen.append(`
                <option value="${e.kitchen_id},${e.id}">${e.kitchen_name} - ${e.name}</option>
            `);
        })
    }
    //
    slctMenuCls.html(`<option value="">- Choose -</option>`);
    MenuClass.forEach(function (e) {
        let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
        el.data(e);
        slctMenuCls.append(el)
    });
    //
    slctMenuSubCls.html(`<option value="">- Choose -</option>`);
    MenuSubClass.forEach(function (e) {
        let el = $(`<option value="${e.id}">${e.code} - ${e.name}</option>`);
        el.data(e);
        slctMenuSubCls.append(el)
    });
    m.on('show.bs.modal', function () {
        slctMealTime.val('');
        slctMenuCls.val('');
        slctMenuSubCls.val('');
        slctPrintToKitchen.val('');
        inputMenuName.val('');
        inputMenuPrice.val('');
        inputMenuQty.val('');
        btnSubmit.prop('disabled', true);
    });
    slctMealTime.on('change', validation);
    slctMenuCls.on('change', validation);
    slctMenuSubCls.on('change', validation);
    slctPrintToKitchen.on('change', validation);
    inputMenuName.on('change paste keyup input blur', validation);
    inputMenuPrice.on('change paste keyup input blur', validation);
    inputMenuQty.on('change paste keyup input blur', validation);
    btnSubmit.on('click', function () {
        let e = slctPrintToKitchen.val().split(',');
        let newMenu = {
            //code: '',
            name: inputMenuName.val(),
            //short_name: '',
            //description: '',
            outlet_id: App.outlet.id,
            status: 'O',
            menu_class_id: slctMenuCls.val(),
            menu_group_id: slctMenuSubCls.val(),
            meal_time_id: slctMealTime.val(),
            menu_price: parseFloat(inputMenuPrice.data('value')),
            //unit_cost,
            //menu_type,
            //product_id,
            //recipe_id,
            //recipe_qty,
            //is_promo_enabled,
            //is_export_cost,
            //is_print_after_total,
            //is_disable_change_price,
            is_tax_included: 'N',
            is_sevice_included: 'N',
            print_kitchen_id: e[0],
            print_kitchen_section_id: e[1],
            created_by: App.user.id,
            image: ''
        };
        let insertOutletMenu = SQL('insert into inv_outlet_menus set ?', newMenu);
        if (!insertOutletMenu.error) {
            newMenu.id = insertOutletMenu.data.insertId;
            m.modal('hide');
            addOrderMenu(newMenu, inputMenuQty.val());
            loadMenu()
        }
    });
};
let printBilling = function () {
    if (!roleDetector('printbill')) {
        El.btnPrintBilling.hide();
        return;
    }
    El.btnPrintBilling.show();
    El.btnPrintBilling.on('click', function () {
        let orderId = orderIds.split(',')[0];
        Printing({orderId: orderId});
    })
};
let reprintBilling = function () {
    let m = El.modalReprintBill;
    if (isOlder) {
        if (!App.role.reprintbill) {
            El.btnReprintBilling.hide();
            return;
        }
    } else {
        El.btnReprintBilling.hide();
        return;
    }
    El.btnReprintBilling.show();
    m.on('show.bs.modal', function () {
        let orderId, ids = [];
        let tbody = m.find('tbody');
        let addRow = function (e) {
            let row = $('<tr>');
            let td = $('<td>')
            let btn = $('<button class="btn btn-xs btn-danger">Print</button>');
            row.append(`
                <td>${e.name}</td>
                <td style="text-align: right;">${e.parent ? '' : rupiahJS(e.payment_amount)}</td>
                <td style="text-align: right;">${e.parent ? '' : rupiahJS(e.tip_amount)}</td>
                <td style="text-align: right;">${e.parent ? '' : rupiahJS(e.change_amount)}</td>
            `, td.append(btn))
            btn.data(e);
            return row;
        };
        tbody.html('')
        Payments.forEach(function (e) {
            orderId = e.order_id;
            ids.push(e.id);
            e.parent = false;
            tbody.append(addRow(e))
        });
        tbody.prepend(addRow({
            id: ids,
            order_id: orderId,
            name: '/* OVERALL */',
            parent: true
        }))
        tbody.find('button').off('click');
        tbody.find('button').on('click', function () {
            let data = $(this).data();
            if (data.parent) {
                Printing({orderId: data.order_id, paymentId: data.id, splitted: true});
            } else {
                Printing({orderId: data.order_id, paymentId: data.id});
            }
        });
    });
};
let voidBilling = function () {
    if (!roleDetector(null, 'voidbill')) {
        El.btnVoidBilling.hide();
        return;
    }
    let m = El.modalVoidBill;
    let notes = El.modalVoidBill.find('#notes');
    let submit = El.modalVoidBill.find('#submit');
    El.btnVoidBilling.show();
    El.btnVoidBilling.on('click', function () {
        m.modal('show');
    });
    submit.on('click', function(){
        submit.prop('disabled', true);
        let userId = App.user.id;
        let orderId = orderIds.split(',')[0];
        let getDate = SQL('select NOW() now');
        let datetime = getDate.data[0].now;
        //
        let posOrders = {
            status: 5,
            reopen_notes: "void bill after payment",
            reopen_date: datetime,
            reopen_by: userId,
            canceled_by: userId,
            cancellation_notes: notes.val()
        };
        let posOrdersArr = [];
        let posOrdersVal = [];
        for (let i in posOrders) {
            if (posOrders[i] === undefined) delete posOrders[i];
            else {
                posOrdersArr.unshift(i + '=?')
                posOrdersVal.unshift(posOrders[i])
            }
        }
        let updatePosOrder = SQL(`update pos_orders set ${posOrdersArr.join()} where id in (${orderIds})`, posOrdersVal);
        if (!updatePosOrder.error) {
            goHome({success: true, response: updatePosOrder.data});
        } else {
            goHome({success: false, response: updatePosOrder.error})
        }
    })
    m.on('show.bs.modal', function () {
        submit.prop('disabled', false)
    })
};
let discountBilling = function () {
    let raw = SQL(`select * from mst_pos_discount where code != '$$' and status = 1`);
    if (!roleDetector('discountbill', 'discountbillafterpayment')) {
        El.modalQty.find('#discount-percent select').data('db', raw.data);
        El.btnDiscountBilling.hide();
        return;
    }
    let modal = El.modalDiscountBill;
    let type = modal.find('#type');
    let auto = modal.find('#automate-promo');
    let manual = modal.find('#manual-promo');
    let manualClass = modal.find('#manual-promo-class select');
    let manualValue = modal.find('#manual-promo-value input');
    let amount = modal.find('#amount');
    let labelNewDiscount = modal.find('#new-discount');
    let labelNett = modal.find('#nett');
    let btnSubmit = modal.find('#submit');
    let validation = function () {
        let typeVal = type.val();
        let manualClassVal = manualClass.val();
        let manualValueVal = parseFloat(manualValue.data('value'));
        manualClass.find('option').attr('has', 0);
        manualClass.find('option[value=""]').attr('has', 1);
        let menus = El.orderMenu.bootstrapTable('getData').filter(function(m, i){
            if (m.is == 'order') {
                manualClass.find(`option[value="${m.menu_class_id}"]`).attr('has', 1)
                return 1
            }
            return 0
        });
        let {total, discount, tax, servicecharge} = getSummary(0);
        let patchedMenu = menus.slice(0);

        manual.hide();
        auto.hide();
        manualClass.find('option[has="1"]').show();
        manualClass.find('option[has="0"]').hide();

        if ($(this).attr('id') == 'type') {
            manualValue.data('value', 0);
            manualValue.data('display', '');
            manualValue.val('');
        }
        if (typeVal == 'manual-amount' || typeVal == 'manual-percentage') {
            if (manualClassVal) {
                patchedMenu = patchedMenu.filter(function (e) {
                    return e.menu_class_id == manualClassVal ? 1 : 0
                });
            }
            manual.show();
        } else if (typeVal) {
            let tempMenu = [];
            let data = raw.data.filter(function (e) {
                return e.id == typeVal ? 1 : 0
            })[0];
            let promos = loadPredifinedPromoId(data);
            //
            auto.show();
            auto.find('div[this]').html('');
            type.data(promos || {});
            for (let p in promos) {
                let prom = promos[p];
                tempMenu = tempMenu.concat(patchedMenu.filter(function(e){
                    return e.menu_class_id == p ? 1 : 0;
                }))
                auto.find('div[this]').append(`
                    <label class="badge btn-primary" style="font-weight: lighter; margin-right: 5px">${prom.label}: ${prom.value} %</label>
                `);
            }
            patchedMenu = tempMenu;
        };
        let shouldPay = new Number(total.value);
        let shouldDiscount = new Number(discount.value);
        patchedMenu.forEach(function (menu) {
            let newDiscount = false;
            let qty = menu.order_qty - menu.order_void;
            let price = menu.price_amount * qty;
            if (typeVal == 'manual-amount') {
                if (manualValueVal >= 0) newDiscount = manualValueVal * qty;
            } else if (typeVal == 'manual-percentage') {
                if (manualValueVal >= 0 && manualValueVal <= 100) newDiscount = price * manualValueVal / 100
            } else if (typeVal) {
                let d = type.data();
                if (d[menu.menu_class_id]) {
                    newDiscount = price * d[menu.menu_class_id].value / 100
                }
            }
            if (newDiscount) shouldDiscount += newDiscount;
        });
        if (shouldPay >= shouldDiscount) {
            btnSubmit.prop('disabled', false);
            labelNewDiscount.html(rupiahJS(shouldDiscount))
            labelNett.html(rupiahJS(shouldPay - shouldDiscount));
            amount.data('value', shouldDiscount - discount.value);
            amount.data('display', rupiahJS(shouldDiscount - discount.value));
            amount.val(rupiahJS(shouldDiscount - discount.value));
        } else {
            btnSubmit.prop('disabled', true);
            labelNewDiscount.html('0.00');
            labelNett.html(rupiahJS(shouldPay));
            amount.data('value', 0);
            amount.data('display', '0.00');
            amount.val('0.00');
        }
    };
    //
    El.modalQty.find('#discount-percent select').data('db', raw.data);
    manualClass.html(El.menuClass.html());
    if (!raw.data.length) type.find('optgroup[this]').hide();
    raw.data.forEach(function (d, i) {
        type.find('optgroup[this]').append(`<option value="${d.id}">${d.name}</option>`);
    });
    type.on('change', validation);
    manualClass.on('change', validation);
    manualValue.on('change paste keyup input blur', validation);
    modal.on('show.bs.modal', function () {
        type.val('');
        auto.hide();
        manual.hide();
        amount.data('value', 0);
        amount.data('display', rupiahJS(0));
        amount.val(rupiahJS(0));
        labelNewDiscount.html(rupiahJS(0));
        labelNett.html(rupiahJS(0));
        btnSubmit.prop('disabled', true);
    });
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        //
        let {total, discount, tax, servicecharge} = getSummary(0);
        let newDiscount = amount.data('value');
        let allDiscount = newDiscount + discount.value;
        let newTotal = total.value - allDiscount;
        let orderId = orderIds.split(',')[0];
        let getDate = SQL('select NOW() now');
        let selectOrder = SQL('select * from pos_orders where id = ?', orderId);
        let {sub_total_amount, tax_total_amount, due_amount, discount_total_amount} = selectOrder.data[0];
        let posOrder = selectOrder.data[0];
        let addDiscountPatched = function () {
            let newPatchDiscount = {
                order_id: orderId,
                discount_percent: 0,
                discount_amount: newDiscount,
                created_by: App.user.id
            }
            let patchDiscount = SQL('insert into pos_patched_discount set ?', newPatchDiscount);
        };
        let updateOrderTaxes = function () {
            let subtotal = sub_total_amount - discount_total_amount;
            let orderTaxes = SQL('select * from pos_order_taxes where order_id = ?', orderId);
            orderTaxes.data.forEach(function (row) {
                if (row.tax_percent) {
                    let taxAmount = subtotal * row.tax_percent / 100;
                    let updateTax = SQL(
                        'update pos_order_taxes set tax_amount = ? where id = ? and tax_id = ?',
                        [taxAmount, row.id, row.tax_id]
                    );
                }
            });
        };
        let updateItem = function () {
            let taxes = SQL('select sum(tax_amount) tax from pos_order_taxes where order_id=?', orderId)
            let totalTaxes = taxes.data[0].tax;
            //
            let posOrderUpdate = {
                sub_total_amount: sub_total_amount,
                discount_total_amount: discount_total_amount,
                tax_total_amount: totalTaxes,
                due_amount: sub_total_amount - discount_total_amount + totalTaxes
            }
            if (isOlder) {
                posOrderUpdate.reopen_notes = "discount billing after payment";
                posOrderUpdate.reopen_date = formalDate(getDate.data[0].now);
                posOrderUpdate.reopen_by = App.user.id;
            }
            let obj = Object.keys(posOrderUpdate)
            SQL(`update pos_orders set ${
                obj.map(function (key) { return key + '=?' }).join()
                } where id=${orderId}`, obj.map(function (key) { return posOrderUpdate[key] })
            );
        };

        discount_total_amount += newDiscount;
        addDiscountPatched();
        updateOrderTaxes();
        updateItem();
        loadOrderMenu();
        loadOrderSummary(orderIds);
        modal.modal('hide');
    });
};
$(document).ready(function () {
    let checkSplitted = anySplitted();
    if (!checkSplitted.length) {
        loadMealTime();
        loadClass();
        loadSubClass();
        loadMenu();
        loadOrder();
        loadOrderMenu();
        loadOrderSummary(orderIds);
        loadBills();
        loadCurrentMealTime();
        cashPayment();
        cardPayment();
        chargeToRoomPayment();
        houseUsePayment();
        voucherPayment();
        cityLedgerPayment();
        noPostPayment();
        multiPayment();
        splitBill();
        mergeBill();
        saveOrderNote();
        manualPrint();
        openCashDraw();
        openMenu();
        printBilling();
        reprintBilling();
        voidBilling();
        discountBilling();
        addBillTip();
        //
        initCheckListBoxes();
        El.paymentBtn.attr('disabled', 1);
        El.btnPrintBilling.attr('disabled', 1);
        El.btnOrderNote.attr('disabled', 1);
        El.btnPrintOrder.attr('disabled', 1);
        El.btnPayNoPost.removeAttr('disabled');
        //
        if (OrderMenu) {
            if (OrderMenu.length) {
                El.paymentBtn.removeAttr('disabled');
                El.btnPrintBilling.removeAttr('disabled');
                El.btnOrderNote.removeAttr('disabled');
                El.btnPrintOrder.removeAttr('disabled');
            }
        }
        El.paymentBtn.on('click', function () {
            let sum = getSummary(0);
            let html = loadOrderSummary4modal(sum);
            El.modalMergeBill.find('#home-total').html(sum.grandtotal.value);
            El.modalMergeBill.find('#grandtotal').html(sum.grandtotal.value);
            if ($(this).attr('id') == 'discount-bill') {
                let el = $('<div>');
                el.append(html);
                el.find('.discount-exception').remove();
                $('.modal-body div#info').html(el.html());
            } else {
                $('.modal-body div#info').html(html);
            }
        });
        App.virtualKeyboard();
    } else {
        let m = El.modalAnySplitted;
        //
        m.modal({backdrop: 'static', keyboard: false});
        m.on('show.bs.modal', function () {
            let div = m.find('#splitted-links');
            div.html('');
            checkSplitted.forEach(function(d){
                let {id, code, status} = d;
                code = code.replace(/\#/g, ' #')
                if (status == '0' || status == '1' || status == '6') {
                    div.append(`
                        <a class="btn btn-app payment-btn" data-toggle="modal" href="/order/${id}" id="pay-cash">
                            <label style="margin-top: 5px">${code}</label>
                        </a>
                    `);
                }
            })
        });
        m.modal('show');
    }
    $('#bottom-navbar').css({
        'z-index': 1000,
        'position': 'fixed',
        //'width': $('.content').width() + 30 + 'px',
        'bottom': '15px'
    });
});
