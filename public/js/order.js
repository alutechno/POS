let Menu, MealTime, MenuClass, MenuSubClass, Order, OrderMenu,
    Taxes = [], Summary = [],
    orderIds = window.location.pathname.split('/')[2].replace(/\-/g, ','),
    HouseUse = {}, Charge2Room = {},
    El = {
        menu: $('div#menu'),
        order: $('div#order'),
        mealTime: $('div#meal-time'),
        menuClass: $('select#menu-class'),
        menuSubClass: $('select#menu-sub-class'),
        menuFinder: $('input#menu-finder'),
        orderMenu: $('table#order-menu'),
        tableSummary: $('table#order-summary'),
        openCashDraw: $('button#open-cash-draw'),
        paymentBtn: $('a.payment-btn'),
        modalQty: $('div#modal-qty'),
        modalCash: $('div#modal-cash'),
        modalCard: $('div#modal-card'),
        modalCharge2Room: $('div#modal-charge-to-room'),
        modalNoPost: $('div#modal-no-post'),
        modalHouseUse: $('div#modal-house-use'),
        modalSplit: $('div#modal-split'),
        modalMerge: $('div#modal-merge'),
        modalPrint: $('div#modal-print'),
        modalOpenMenu: $('div#modal-open-menu'),
        modalAddNote: $('div#modal-add-note'),
        modalVoid: $('div#modal-void'),
        btnPayCash: $('a#pay-cash'),
        btnPayCard: $('a#pay-card'),
        btnPayChargeToRoom: $('a#pay-charge-to-room'),
        btnPayHouseUse: $('a#pay-house-use'),
        btnPayCityLedger: $('a#pay-city-ledger'),
        btnPayVoucher: $('a#pay-voucher'),
        btnPaySplit: $('a#pay-split'),
        btnPayNoPost: $('a#pay-no-post'),
        btnMergeTable: $('a#merge-tables'),
        btnOpenCashDraw: $('button#open-cash-draw'),
        btnPrintBilling: $('button#print-billing'),
        btnOrderNote: $('a#order-note'),
        btnPrintOrder: $('a#print-order'),
        btnOpenMenu: $('a#open-menu')
    };
let delay = (function () {
    let timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();
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
    El.openCashDraw.on('click', function () {
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
let Printing = function (param) {
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
let Payment = function (param, splitted) {
    let {
        order_notes, payment_type_id, payment_amount,
        change_amount, folio_id, card_no, house_use_id,
        total_amount, status
    } = param;
    let userId = App.user.id;
    let tranBatchId = App.posCashier.id;
    let orderId = orderIds.split(',')[0];
    let getDate = SQL('select NOW() now');
    let datetime = getDate.data[0].now;
    //
    let posOrders = {
        status: status || 2,
        order_notes,
        modified_by: userId,
        modified_date: datetime,
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
    if (!updatePosOrder.error) {
        if (status === 4) {
            Printing({orderId: orderId, payment: payment_amount});
            return {success: true, response: updatePosOrder.data};
        } else {
            let posPaymentDetail = {
                order_id: orderId,
                payment_type_id,
                payment_amount,
                change_amount,
                folio_id,
                card_no,
                house_use_id,
                total_amount,
                created_by: userId
            };
            for (let i in posPaymentDetail) {
                if (posPaymentDetail[i] === undefined) delete posPaymentDetail[i];
            }
            let insertPosPaymentDetail = SQL('insert into pos_payment_detail set ?', posPaymentDetail);
            if (!insertPosPaymentDetail.error) {
                Printing({orderId: orderId, payment: payment_amount});
                return {success: true, response: insertPosPaymentDetail.data};
            } else {
                return {success: false, response: insertPosPaymentDetail.error}
            }
        }
    } else {
        return {success: false, response: updatePosOrder.error}
    }
};
let getSummary = function (key) {
    let obj = {};
    let total = 0;
    let discount = 0;
    let taxes = 0;
    Taxes.forEach(function (el) {
        taxes += el.tax_amount;
        obj[el.tax_name.toLowerCase().replace(/\s/g, '')] = {
            label: el.tax_name, value: el.tax_amount
        }
    });
    Summary.forEach(function (el) {
        total += el.price;
        discount += el.discount;
    })
    obj.discount = {label: 'Discount', value: discount};
    obj.total = {label: 'Total', value: total};
    obj.grandtotal = {label: 'Grand Total', value: total - discount + taxes};
    if (obj.hasOwnProperty(key)) return obj[key];
    return obj;
};
let paymentHasDone = function (param) {
    if (param.success) {
        setTimeout(function () {
            window.location.href = '/'
        }, 3000);
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
}
let loadMealTime = function () {
    let mealTime = SQL(`select * from ref_meal_time where DATE_FORMAT(now(), '%H:%i:%s') BETWEEN start_time and end_time`);
    MealTime = {};
    El.mealTime.html('<code style="margin-right: 5px">Meal Time</code>');
    if (!mealTime.error) {
        mealTime.data.forEach(function (e) {
            MealTime[e.id] = e
            El.mealTime.append(`
                <label class="badge btn-info" style="font-weight: lighter">
                    ${e.name}
                </label>
            `)
        })
    }
};
let loadClass = function () {
    let menuClass = SQL(`select a.*
		from ref_outlet_menu_class a,pos_avail_menu_class b
		where a.id=b.menu_class_id and a.status=1 and b.outlet_Id=`+App.outlet.id+' order by a.name');
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
        loadMenu({class: val, name: El.menuFinder.val()});
    })
};
let loadSubClass = function (filter) {
	console.log(filter)
    if (filter === undefined) {
        let menuSubClass = SQL(`select c.*
			from ref_outlet_menu_class a,pos_avail_menu_class b,ref_outlet_menu_group c
			where a.id=b.menu_class_id
			and a.id=c.menu_class_id
			and c.status=1
			and b.outlet_Id=`+App.outlet.id+' order by c.name');
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
            loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
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
        let query = App.mealTimeMenu ? ` and meal_time_id in (${Object.keys(MealTime).join()})` : ''
        let menu = SQL(`select * from inv_outlet_menus where status = 1 and outlet_id=${App.outlet.id} ${query} order by name`);
        Menu = [];
        if (!menu.error) {
            let inputQty = El.modalQty.find('#qty');
            let btnSubmit = El.modalQty.find('#submit');
            let menuBg = El.menu.find('.menu-bg');
            let menuFinder = El.menuFinder;
            //
            El.menu.html('');
            Menu = menu.data.map(function (e) {
                e.url_ = 'http://103.43.47.115:3000';
                e.menu_price_ = rupiahJS(e.menu_price);
                let el = $(`
                    <div class="col-lg-3 col-sm-4 col-xs-4 menu-item"
                        menu-id="${e.id}" menu-name="${e.name.toLowerCase()}"
                        menu-class="${e.menu_class_id}"
                        menu-sub-class="${e.menu_group_id}">
                        <div class="small-box">
                            <div class="small-box-footer menu">
                                <div class="menu-bg"></div>
                                <div class="menu-info text-center">
                                    <div class="menu-info-name">${e.name}</div>
                                    <div class="menu-info-price">${e.menu_price_}</div>
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
            menuFinder.off('blur');
            menuFinder.on('blur', function () {
                loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
            });
            //
            if (App.role.ordermenu) {
                El.menu.find('.menu-item').on('click', function () {
                    inputQty.val('')
                    btnSubmit.prop('disabled', true);
                    El.modalQty.find('h4').html($(this).data('name'));
                    El.modalQty.modal('show');
                    El.modalQty.data($(this).data());
                });
                inputQty.on('blur', function () {
                    let qty = inputQty.data('value');
                    btnSubmit.prop('disabled', true);
                    if (parseInt(qty) > 0) {
                        btnSubmit.prop('disabled', false);
                    }
                })
                btnSubmit.on('click', function () {
                    let qty = inputQty.data('value');
                    let item = El.modalQty.data();
                    addOrderMenu(item, parseInt(qty));
                });
            }
        }
    } else {
        let selector = '';

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
    let order = SQL(`
        select a.id, a.code,b.table_no from pos_orders a,mst_pos_tables b
        where a.table_id=b.id
        and a.outlet_id=b.outlet_id and a.id in(${orderIds})
    `);
    if (!order.error) {
        Order = {};
        El.order.html('<code style="margin-right: 5px">Order Number / Table</code>');
        order.data.forEach(function (e) {
            Order[e.id] = e;
            El.order.append(`
                <label class="badge btn-primary" style="font-weight: lighter; margin-right: 5px">
                    ${e.code} / ${e.table_no}
                </label>
            `)
        });
    }

};
let loadOrderMenu = function () {
    let mVoid, m = El.modalVoid;
    let inputQty = m.find('input');
    let btnSubmit = m.find('#submit');
    let orderMenu = SQL(`
        select
            @rownum := @rownum + 1 as no,
            o.outlet_menu_id, b.name, o.price_amount, o.order_id, o.menu_class_id,
            o.order_qty, if (v.order_void < 0, v.order_void, 0) order_void,
            (o.total_amount + if (v.total_amount < 0, v.total_amount, 0)) total_amount,
            format(o.total_amount + if (v.total_amount < 0, v.total_amount, 0),2) total_amount_
        from (
            select
                a.menu_class_id, a.outlet_menu_id, a.price_amount, sum(a.total_amount) total_amount, a.order_id,
                sum(a.order_qty) as order_qty
            from pos_orders_line_item a
            where order_qty > 0 and order_id in (${Object.keys(Order).join()})
            group by order_id, outlet_menu_id
        ) o
        left join (
            select
                a.outlet_menu_id, sum(a.order_qty) as order_void,
                sum(a.total_amount) total_amount
            from pos_orders_line_item a
            where order_qty < 0 and order_id in (${Object.keys(Order).join()})
            group by order_id, outlet_menu_id
        ) v on o.outlet_menu_id = v.outlet_menu_id
        join inv_outlet_menus b on b.id = o.outlet_menu_id
        cross join (select @rownum := 0) r
        order by no
    `);
    OrderMenu = [];
    if (!orderMenu.error) {
        OrderMenu = orderMenu.data.map(function (e) {
            e.order_void_ = e.order_void ? e.order_void * -1 : ''
            return e;
        });
    }
    window.appendFn = function (value, row, index) {
        return (
            `<div class="pull-right">
                <a class="remove" href="#modal-void" title="Void">
                    <i class="glyphicon glyphicon-remove text-danger"></i>
                </a>
            </div>`
        );
    };
    window.eventFn = {
        'click .remove': function (e, value, row) {
            m.modal('show');
            m.find('#modal-label').html(row.name)
            inputQty.val('');
            btnSubmit.prop('disabled', true);
            mVoid = row;
            //alert('You click remove action, row: ' + JSON.stringify(row));
        }
    };
    inputQty.off('blur');
    inputQty.on('blur', function () {
        let val = inputQty.data('value');

        btnSubmit.prop('disabled', true);
        val = parseInt(val);
        if (val) {
            if ((mVoid.order_qty + mVoid.order_void) >= val) {
                btnSubmit.prop('disabled', false);
            }
        }
    });
    btnSubmit.off('click');
    btnSubmit.on('click', function () {
        let val = inputQty.data('value');
        val = parseInt(val) * -1;
        addOrderMenu({
            id: mVoid.outlet_menu_id,
            menu_class_id: mVoid.menu_class_id,
            outlet_id: App.outlet.id,
            menu_price: mVoid.price_amount,
            name: mVoid.name
        }, val)
        m.modal('hide');
    })
    El.orderMenu.bootstrapTable('load', OrderMenu);
    if (!App.role.voidmenu) {
        El.orderMenu.bootstrapTable('hideColumn', 'void');
    }
};
let loadOrderSummary = function () {
    let queries = SQL(`
        select
            tax_id, sum(a.tax_amount) tax_amount, b.name tax_name
        from pos_order_taxes a, mst_pos_taxes b
        where a.tax_id=b.id and a.order_id in (${orderIds})
        group by tax_id;

        select
            b.name, sum(price_amount * order_qty) price,
            IFNULL(sum(c.discount_amount),0) discount
        from pos_orders_line_item a
        left join pos_patched_discount c on a.id=c.order_line_item_id, ref_outlet_menu_class b
        where a.menu_class_id=b.id and a.order_id in (${orderIds})
        group by b.name
    `)
    Taxes = queries.data[0];
    Summary = queries.data[1];
    let sum = getSummary();
    let parent = El.tableSummary.find('tbody')

    parent.html('');
    Summary.forEach(function (el) {
        parent.append(`
            <tr>
                <td colspan="3" align="left">${el.name}</td>
                <td align="right">${rupiahJS(el.price)}</td>
            </tr>
        `)
    });
    ['discount', 'servicecharge', 'tax', 'grandtotal'].forEach(function (e) {
        let {label, value} = sum[e];
        parent.append(`
            <tr>
                <td colspan="3" align="left">${label}</td>
                <td align="right">${rupiahJS(value)}</td>
            </tr>
        `);
    });
};
let addOrderMenu = function (data, qty = 1) {
    let {id, menu_class_id, outlet_id} = data;
    let menu_price = data.menu_price * qty;
    let totalDiscount = 0, nettPrice = menu_price;
    let order = Order[Object.keys(Order)[0]];
    let orderItemId, patchDiscountIds = [], orderTaxIds = {}, posOrder;
    //
    let addOrderItem = function () {
        let newLineItem = {
            order_id: order.id,
            menu_class_id: menu_class_id,
            outlet_menu_id: id,
            serving_status: 0,
            order_qty: qty,
            price_amount: data.menu_price,
            total_amount: menu_price,
            created_by: App.user.id
        }
        let orderItem = SQL('insert into pos_orders_line_item set ?', newLineItem);
        orderItemId = orderItem.data.insertId;
    };
    let addDiscountPatched = function () {
		let day = SQL('select LOWER(DAYNAME(NOW())) a');
        let promos = SQL(`select * from pos_menu_promos where outlet_menu_id=? and is_avail_${day.data[0].a}='Y'`, id);
        promos.data.forEach(function (promo) {
            let discount = 0;
            if (promo.discount_amount > 0) {
                discount = promo.discount_amount
            } else {
                discount = menu_price / 100 * promo.discount_percent;
            }
            totalDiscount += discount * qty;

            let newPatchDiscount = {
                order_line_item_id: orderItemId,
                menu_class_id: menu_class_id,
                outlet_menu_id: id,
                promo_id: promo.id,
                discount_amount: discount*qty,
                created_by: App.user.id
            }
            let patchDiscount = SQL('insert into pos_patched_discount set ?', newPatchDiscount);
            patchDiscountIds.push(patchDiscount.data.insertId);
        })
    }
    let updateOrderTaxes = function () {
        let outletTaxes = SQL('select is_sevice_included, is_tax_included from inv_outlet_menus where id = ?', id);
        let {is_tax_included, is_sevice_included} = outletTaxes.data[0];
        let orderTaxes = SQL('select * from pos_order_taxes where order_id = ?', order.id);
        if (is_tax_included != 'Y' || is_sevice_included != 'Y') {
            if (is_tax_included != 'Y') {
                let rows = orderTaxes.data.filter(function (row) {
                    return row.tax_id == 2 ? 1 : 0
                });
                if (rows[0]) {
                    let taxAmount = rows[0].tax_amount + (menu_price * rows[0].tax_percent / 100);
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
                    let taxAmount = rows[0].tax_amount + (menu_price * rows[0].tax_percent / 100);
                    let updateOrderTax1 = SQL(
                        'update pos_order_taxes set tax_amount = ? where id = ? and tax_id = ?',
                        [taxAmount, rows[0].id, rows[0].tax_id]
                    );
                    orderTaxIds[rows[0].id] = rows[0].tax_amount;
                }
            }
            //todo: additional tax & service
        }
    }
    let updateItem = function () {
        let selectOrder = SQL('select * from pos_orders where id = ?', order.id);
        let {sub_total_amount, tax_total_amount, due_amount, discount_total_amount} = selectOrder.data[0];
        let taxes = SQL('select sum(tax_amount) tax from pos_order_taxes where order_id=?', order.id)
        let totalTaxes = taxes.data[0].tax;

        posOrder = selectOrder.data[0]
        sub_total_amount += menu_price;
        discount_total_amount += totalDiscount;
        SQL('update pos_orders set sub_total_amount=?, discount_total_amount=?, tax_total_amount=?, due_amount=? where id=?', [
            sub_total_amount,
            discount_total_amount,
            totalTaxes,
            sub_total_amount - discount_total_amount + totalTaxes,
            order.id
        ]);
    }

    addOrderItem();
    addDiscountPatched();
    updateOrderTaxes();
    updateItem();
    loadOrderMenu();
    loadOrderSummary();
    El.modalQty.modal('hide');
    //El.orderMenu.bootstrapTable('insertRow', {
    //    index: OrderMenu.length,
    //    row: {
    //        "menu_outlet_id": data.id,
    //        "total_amount": menu_price,
    //        "order_id": orderIds.split(',')[0],
    //        "order_qty": qty,
    //        "id": orderItemId,
    //        "name": data.name,
    //        "no": OrderMenu.length + 1,
    //        "total_amount_": rupiahJS(menu_price)
    //    }
    //});
};
let mergeOrder = function () {
    if (!App.role.join) {
        El.btnMergeTable.hide();
        return;
    }
    let modal = El.modalMerge;
    let pattern = '#check-list-box li';
    let grandtotal = getSummary('grandtotal').value;
    let ulCheckListBox = modal.find('#check-list-box');
    let lblHomeTotal = modal.find('#home-total');
    let lblGrandtotal = modal.find('#grandtotal');
    let btnSubmit = modal.find('#submit');
    let paths = Object.keys(Order);
    //
    El.btnMergeTable.show();
    //
    let tableList = SQL(`
        select * from (
            select
                a.id,a.table_no,a.cover, b.id order_id, b.num_of_cover guest,
                b.sub_total_amount, b.discount_total_amount, b.tax_total_amount,
                b.due_amount
            from mst_pos_tables a
            left join pos_orders b on a.id=b.table_id and b.status in (0,1)
            where a.outlet_id=?
            group by a.id order by b.id DESC
        ) x
        where order_id is not null and order_id not in (${Object.keys(Order).join()})
        order by table_no, id
    `, App.outlet.id);
    ulCheckListBox.html('');
    tableList.data.forEach(function (e) {
        let li = $(`
            <li id="merge-w-${e.order_id}" class="list-group-item" data-color="info">
                Table #${e.table_no}
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
            paths = Object.keys(Order).concat(a)
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
                [home, key, App.user.id, datetime, App.user.id]
            )
        });
        window.location.href = '/order/' + paths.join('-')
    });
    //
    $('a[href="#modal-merge"]').on('click', function () {
        El.modalMerge.find('li.active').click();
    })
};
let cashPayment = function () {
    if (!App.role.cash) {
        El.btnPayCash.hide();
        return;
    }
    let modal = El.modalCash;
    let total = getSummary('total').value;
    let discount = getSummary('discount').value;
    let service = getSummary('servicecharge').value;
    let tax = getSummary('tax').value;
    let grandtotal = getSummary('grandtotal').value;
    let change = 0;
    //
    let btnSubmit = modal.find('#submit');
    let lblChange = modal.find('#change');
    let inputAmount = modal.find('#amount');
    //
    El.btnPayCash.show();
    inputAmount.on('blur', function () {
        let value = $(this).data('value');
        change = parseFloat(value) - parseFloat(grandtotal);
        if (change >= 0) {
            btnSubmit.prop('disabled', false)
            lblChange.html(rupiahJS(change));
        } else {
            btnSubmit.prop('disabled', true)
            lblChange.html(rupiahJS(0));
        }
    });
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: 1,
            grandtotal: grandtotal,
            payment_amount: inputAmount.data('value'),
            change_amount: change,
        });
        paymentHasDone(pay);
    });
};
let cardPayment = function () {
    if (!App.role.card) {
        El.btnPayCard.hide();
        return;
    }
    let modal = El.modalCard;
    let total = getSummary('total').value;
    let discount = getSummary('discount').value;
    let service = getSummary('servicecharge').value;
    let tax = getSummary('tax').value;
    let grandtotal = getSummary('grandtotal').value;
    //
    let selectBankType = modal.find('#bank-type');
    let selectCcType = modal.find('#cc-type');
    let inputCardSwiper = modal.find('#card-swiper');
    let inputCardNo = modal.find('#card-no');
    let inputCustomerName = modal.find('#customer-name');
    let btnSubmit = modal.find('#submit');
    let validation = function () {
        let val1 = selectBankType.val();
        let val2 = inputCardNo.val();
        let val3 = inputCustomerName.val();
        btnSubmit.prop('disabled', true);
        if (selectCcType.val() == 'credit') {
            if (val1 && val3 && val3) btnSubmit.prop('disabled', false);
        } else {
            inputCustomerName.val('');
            if (val1 && val2) btnSubmit.prop('disabled', false);
        }
    };
    //
    El.btnPayCard.show();
    //
    let bankList = SQL(`select id, code, name, description from ref_payment_method where category = 'CC' and status = '1' order by name`);
    selectBankType.html('<option value="">- Choose -</option>');
    bankList.data.forEach(function (bank) {
        selectBankType.append(`<option value="${bank.id}">${bank.code} - ${bank.name}</option>`);
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
    inputCardNo.on('change', validation);
    inputCustomerName.on('change', validation);
    selectBankType.on('change', validation);
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            payment_type_id: selectBankType.val(),
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            change_amount: 0,
        });
        paymentHasDone(pay);
    });
};
let chargeToRoomPayment = function () {
    if (!App.role.chargeroom) {
        El.btnPayChargeToRoom.hide();
        return
    }
    let modal = El.modalCharge2Room;
    let total = getSummary('total').value;
    let discount = getSummary('discount').value;
    let service = getSummary('servicecharge').value;
    let tax = getSummary('tax').value;
    let grandtotal = getSummary('grandtotal').value;
    let folio_id, data;
    //
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
    let btnSubmit = modal.find('#submit');
    //
    El.btnPayChargeToRoom.show()
    //
    let houseGuest = SQL(`select * from v_in_house_guest`);
    selectCustomer.html('<option value="">- Choose -</option>');
    houseGuest.data.forEach(function (e) {
        Charge2Room[e.folio_id] = e;
        let el = $(`<option value="${e.folio_id}">[${e.room_type} / ${e.room_no}] - ${e.cust_firt_name} ${e.cust_last_name}</option>`);
        selectCustomer.append(el);
    });
    selectCustomer.on('change', function () {
        let val1 = selectCustomer.val();
        data = 0;
        if (val1) {
            data = houseGuest.data.filter(function (e) {
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
            lblVipType.html(data.vip_type);
            if (data.is_cash_bases.toLowerCase() === 'n') {
                btnSubmit.prop('disabled', false);
            }
        }
    });
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            order_notes: txAreaNote.val(),
            folio_id: data.folio_id,
            payment_type_id: 16,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            change_amount: 0
        });
        paymentHasDone(pay);
    });
};
let houseUsePayment = function () {
    if (!App.role.houseuse) {
        El.btnPayHouseUse.hide();
        return
    }
    ;
    let modal = El.modalHouseUse;
    let total = getSummary('total').value;
    let discount = getSummary('discount').value;
    let service = getSummary('servicecharge').value;
    let tax = getSummary('tax').value;
    let grandtotal = getSummary('grandtotal').value;
    let house_use_id, data;
    //
    let selectHouseUse = modal.find('#house-use');
    let lblPeriod = modal.find('#period');
    let lblHouseUseInfo = modal.find('#house-use-info');
    let lblCostCenter = modal.find('#cost-center');
    let lblMaxSpent = modal.find('#max-spent');
    let lblCurrentBalance = modal.find('#current-balance');
    let txAreaNote = modal.find('#note');
    let btnSubmit = modal.find('#submit');
    //
    El.btnPayHouseUse.show();
    //
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
    selectHouseUse.on('change', function () {
        let val1 = selectHouseUse.val();
        data = 0;
        if (val1) {
            data = houseUseList.data.filter(function (e) {
                return e.house_use_id == val1 ? 1 : 0;
            })[0];
            house_use_id = (data.folio_id);
            lblCostCenter.html(data.cost_center);
            lblHouseUseInfo.html(data.house_use);
            lblCurrentBalance.html(rupiahJS(data.current_transc_amount));
            lblMaxSpent.html(rupiahJS(data.max_spent_monthly));
            lblPeriod.html(data.period);
            let balance = parseFloat(data.max_spent_monthly || 0) - parseFloat(data.current_transc_amount || 0);
            if (balance > 0) {
                if (data.period && (balance - parseFloat(grandtotal) >= 0)) {
                    btnSubmit.prop('disabled', false);
                }
            }
        }
    });
    btnSubmit.prop('disabled', true);
    btnSubmit.on('click', function () {
        btnSubmit.prop('disabled', true);
        let pay = Payment({
            order_notes: txAreaNote.val(),
            house_use_id: data.house_use_id,
            payment_type_id: 17,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            change_amount: 0
        });
        paymentHasDone(pay);
    });
};
let noPostPayment = function () {
    if (!App.role.nopost) {
        El.btnPayNoPost.hide();
        return
    }
    let modal = El.modalNoPost;
    let total = getSummary('total').value;
    let discount = getSummary('discount').value;
    let service = getSummary('servicecharge').value;
    let tax = getSummary('tax').value;
    let grandtotal = getSummary('grandtotal').value;
    //
    let txAreaNote = modal.find('#note');
    let btnSubmit = modal.find('#submit');
    //
    El.btnPayNoPost.show();
    txAreaNote.on('change', function () {
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
        paymentHasDone(pay);
    });
};
let splitPayment = function () {
    if (!App.role.split) {
        El.btnPaySplit.hide();
        return;
    }
    let m = El.modalSplit;
    let grandtotal = getSummary('grandtotal').value;
    let close = m.find('#close');
    let next = m.find('#submit');
    let mode = m.find('#mode');
    let modeCounter = m.find('#mode-counter');
    let noState = m.find('#no-state');
    let state = m.find('#state');
    let payments = m.find('.payment-state');
    let modeLabel0 = m.find('#mode-label-0');
    let modeLabel1 = m.find('#mode-label-1');
    let balance = m.find('#balance');
    let recordList = $('<div>');
    //
    let count = 0;
    let manualMode = m.find('#manual-mode');
    let manualState = m.find('#manual-state');
    let eventState = m.find('#event-state');
    let itemState = m.find('#item-state');
    let recordPayment = function (i, value) {
        return $(`
        <div class="row">
            <div class="col-sm-6">
                <span style="margin-left: 10px;">Payment #${i}</span>
            </div>
            <div class="col-sm-6 text-right">
                <span style="margin-right: 13px;">
                    ${rupiahJS(value)}
                </span>
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
                    <span for="usr">Pay with</span>
                </div>
                <div class="col-sm-6 text-right">
                    <input id="${id}-paywith" type="currency" class="form-control">
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
        let options = El.modalCard.find('#bank-type').html();
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
                <div class="col-sm-9">
                    <div class="form-group">
                        <span>Bank</span>
                        <select class="form-control" id="${id}-card_type">
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
                        <span for="usr">Number:</span>
                        <input id="${id}-cardno" type="text" class="form-control">
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
        let options = El.modalCharge2Room.find('#customer').html();
        let pay = $(`
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
                            <span id="${id}-max_spent_monthly" class="pull-right"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Current Transaction Amount</span>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <span id="${id}-current_transc_amount" class="pull-right"></span>
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
    El.btnPaySplit.show();
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
        let activePayment = payments.filter(function (i, e) {
            return ($(this).is(':visible'))
        });
        if (activePayment.length) {
            let limit = parseInt(modeCounter.val());
            let bayar = parseFloat(activePayment.find('[id*="-amount"]').data('value'));
            let nextBalance = balance.val() - parseFloat(bayar);
            let an = activePayment.find('[id*="-mode"]').find('select').val();
            let d = {};
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

                console.info('Splitbill > saving & printing start #' + count);
                let pay = Payment(d);
                if (pay.success) {
                    activePayment.hide();
                    next.enable();
                    next.click();
                    console.info('Splitbill > saving & printing done #' + count);
                } else {
                    console.error('Splitbill > saving & printing error #' + count);
                    alert(`Error occurrence, result : ${JSON.stringify(pay.response)}`);
                }
            } else if ((nextBalance <= 0) || (count == limit)) {
                next.disable();
                balance.val(nextBalance);
                balance.html(rupiahJS(nextBalance));
                recordList.append(recordPayment(count, bayar));

                console.info('Splitbill > saving & printing start #' + count);
                let pay = Payment(d);
                if (pay.success) {
                    activePayment.hide();
                    mode.val('')
                    close.enable();
                    state.hide();
                    noState.hide();
                    console.info('Splitbill > saving & printing done #' + count);
                    console.info('Splitbill > finished!');
                    paymentHasDone(pay);
                } else {
                    console.error('Splitbill > saving & printing error #' + count);
                    alert(`Error occurrence, result : ${JSON.stringify(res.result)}`);
                }
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
        balance.val(grandtotal);
        modeCounter.val('');
        recordList.html('');
        next.disable();
        modeCounter.parent().prev().hide()
        modeCounter.hide();
        noState.show();
        state.hide();
        count = 0;
    });
    modeCounter.keyboard({layout: 'num'});
    recordList.insertBefore(balance.closest('.row'));
    m.modal({backdrop: 'static', keyboard: false});
    m.modal('hide');
};
let saveOrderNote = function () {
    if (!App.role.note) {
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
    txAreaNote.on('change', function () {
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
    if (!App.role.printorder) {
        El.btnPrintOrder.hide();
        return;
    }
    let modal = El.modalPrint;
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
        ngAjax({orderId: Object.keys(Order)}, function () {
            modal.modal('hide');
        })
    });
    btnRePrint.on('click', function () {
        btnRePrint.attr('disabled', 1);
        ngAjax({reprint: 1, orderId: Object.keys(Order)}, function () {
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
            ngAjax({orderId: Object.keys(Order), printer}, function () {
                modal.modal('hide');
            });
        });
    });
};
let openMenu = function () {
    if (!App.role.openmenu) {
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
    }
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
    inputMenuName.on('blur', validation);
    inputMenuPrice.on('blur', validation);
    inputMenuQty.on('blur', validation);
    btnSubmit.on('click', function () {
        let e = slctPrintToKitchen.val().split(',');
        let newMenu = {
            //code: '',
            name: inputMenuName.val(),
            //short_name: '',
            //description: '',
            outlet_id: App.outlet.id,
            status: '1',
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
    //if (!App.role.printbill) {
    //    El.btnPrintBilling.hide();
    //    return;
    //}
    El.btnPrintBilling.show();
    El.btnPrintBilling.on('click', function () {
        let orderId = orderIds.split(',')[0];
        Printing({orderId: orderId, payment: 0});
    })
};
$(document).ready(function () {
    let {
        nopost, cash, chargeroom, card,
        voucher, cityledger, join,
        printorder, openmenu, note,
        split, cashdraw, voidmenu,
        houseuse
    } = App.role;
    //
    loadMealTime();
    loadClass();
    loadSubClass();
    loadMenu();
    loadOrder();
    loadOrderMenu();
    loadOrderSummary();
    cashPayment();
    mergeOrder();
    cardPayment();
    chargeToRoomPayment();
    houseUsePayment();
    noPostPayment();
    splitPayment();
    saveOrderNote();
    manualPrint();
    openCashDraw();
    openMenu();
    printBilling();
    //
    initCheckListBoxes();
    El.paymentBtn.on('click', function () {
        let gt = rupiahJS(getSummary('grandtotal').value);
        El.modalMerge.find('#home-total').html(gt)
        El.modalMerge.find('#grandtotal').html(gt)
        $('.modal-body div#info').html(`
            <div class="row">
                <div class="col-lg-6">
                    <span>Total</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span style="margin-right: 13px;" for="subtotal">
                        ${rupiahJS(getSummary('total').value)}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span style="margin-left: 15px;">Discount</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span style="margin-right: 13px;" for="discount">
                        ${rupiahJS(getSummary('discount').value)}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span style="margin-left: 15px;">Service Charge</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span style="margin-right: 13px;" for="service">
                        ${rupiahJS(getSummary('servicecharge').value)}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span style="margin-left: 15px;">Tax</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span style="margin-right: 13px;" for="tax">
                        ${rupiahJS(getSummary('tax').value)}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Grand Total</span>
                </div>
                <div class="col-lg-6 text-right">
                    <span style="margin-right: 13px;" for="total">
                        ${gt}
                    </span>
                </div>
            </div>
            <hr style="margin-top: 5px; margin-bottom: 10px"/>
        `);
    })
    App.virtualKeyboard();
});
