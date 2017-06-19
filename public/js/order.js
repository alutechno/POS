let Menu, MealTime, MenuClass, MenuSubClass, Order, OrderMenu,
    orderIds = window.location.pathname.split('/')[2].replace(/\-/g, ','),
    El = {
        menu: $('div#menu'),
        order: $('div#order'),
        mealTime: $('div#meal-time'),
        menuClass: $('select#menu-class'),
        menuSubClass: $('select#menu-sub-class'),
        menuFinder: $('input#menu-finder'),
        orderMenu: $('table#order-menu'),
        orderTotFood: $('td#order-tot-food'),
        orderTotDiscount: $('td#order-tot-discount'),
        orderTotService: $('td#order-tot-service'),
        orderTotTax: $('td#order-tot-tax'),
        orderTotSum: $('td#order-tot-sum'),
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
        modalVoid: $('div#modal-void')
    };
let delay = (function () {
    let timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();
let rupiahJS = function (val) {
    return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
    .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
        total_amount
    } = param;
    let userId = App.user.id;
    let tranBatchId = App.posCashier.id;
    let orderId = Object.keys(Order)[0];
    let getDate = SQL('select NOW() now');
    let datetime = getDate.data.now;
    //
    let posOrders = {
        status: 2,
        order_notes,
        modified_by: userId,
        modified_date: datetime,
        closing_batch_id: tranBatchId
    };
    let posOrdersArr = [];
    let posOrdersVal = [orderIds];
    for (let i in posOrders) {
        if (posOrders[i] === undefined) delete posOrders[i];
        else {
            posOrdersArr.unshift(i + '=?')
            posOrdersVal.unshift(posOrders[i])
        }
    }
    let updatePosOrder = SQL(`update pos_orders set ${posOrdersArr.join()} where id in (?)`, posOrdersVal);
    if (!updatePosOrder.error) {
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
    } else {
        return {success: false, response: updatePosOrder.error}
    }
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
    let menuClass = SQL('select * from ref_outlet_menu_class where status = 1 order by name');
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
    if (filter === undefined) {
        let menuSubClass = SQL('select * from ref_outlet_menu_group where status = 1 order by name');
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
            El.menu.html('');
            Menu = menu.data.map(function (e) {
                e.menu_price_ = rupiahJS(e.menu_price);
                let el = $(`
                    <div class="col-lg-3 col-sm-4 col-xs-4 menu-item" 
                        menu-id="${e.id}" menu-name="${e.name}"
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
                El.menu.append(el);
                return e;
            });
            El.menu.find('.menu-bg').height(El.menu.find('.menu-bg').parent().width());
            El.menu.find('.menu-item').on('click', function () {
                El.modalQty.find('button#submit').prop('disabled', false);
                El.modalQty.find('h4').html($(this).data('name'));
                El.modalQty.modal('show');
                El.modalQty.data($(this).data());
            });
            El.menuFinder.on('blur', function () {
                loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
            });
            El.modalQty.find('button#submit').on('click', function () {
                let qty = El.modalQty.find('input#qty').data('value');
                let item = El.modalQty.data();
                if (parseInt(qty) > 0) {
                    El.modalQty.find('button#submit').prop('disabled', true);
                    addOrderMenu(item, parseInt(qty));
                }
            });
        }
    } else {
        let selector = '';

        if (filter.class) selector += `[menu-class=${filter.class}]`;
        else selector += `[menu-class]`;

        if (filter.subClass) selector += `[menu-sub-class=${filter.subClass}]`;
        else selector += `[menu-sub-class]`;

        if (filter.name) selector += `[menu-name*='${filter.name}']`;
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
    let orderMenu = SQL(`
        select
            @rownum := @rownum + 1 as no,
            a.outlet_menu_id, a.price_amount, sum(a.total_amount) total_amount, a.order_id, 
            sum(a.order_qty) as order_qty, a.id, b.name, format(sum(a.total_amount),2) as total_amount_
        from pos_orders_line_item a
        join inv_outlet_menus b on b.id = a.outlet_menu_id
        cross join (select @rownum := 0) r
        where order_id in (${Object.keys(Order).join()})
        group by order_id, a.created_date, outlet_menu_id
        order by no
    `);
    OrderMenu = [];
    if (!orderMenu.error) {
        OrderMenu = orderMenu.data;
    }
    El.orderMenu.bootstrapTable('load', OrderMenu);

};
let loadOrderSummary = function () {
    let summary = SQL(`
        select 
            sum(a.sub_total_amount) subtotals, sum(a.discount_total_amount) discounts, 
            sum(b.service_amount) services, sum(c.tax_amount) taxes, sum(a.due_amount) totals
        from pos_orders a
        join (
            select order_id, tax_amount service_amount from pos_order_taxes where tax_id = 1
        ) b on b.order_id = a.id
        join (
            select order_id, sum(tax_amount) tax_amount from pos_order_taxes where tax_id != 1
        ) c on c.order_id = a.id
        where id in (?);
    `, orderIds);
    let row = summary.data[0]
    El.orderTotFood.data('value', row.subtotals);
    El.orderTotFood.html(rupiahJS(row.subtotals));
    El.orderTotDiscount.data('value', row.discounts);
    El.orderTotDiscount.html(rupiahJS(row.discounts));
    El.orderTotService.data('value', row.services);
    El.orderTotService.html(rupiahJS(row.services));
    El.orderTotTax.data('value', row.taxes);
    El.orderTotTax.html(rupiahJS(row.taxes));
    El.orderTotSum.data('value', row.totals);
    El.orderTotSum.html(rupiahJS(row.totals));
    $('.modal-body div#info').html(`
        <div class="row">
            <div class="col-lg-6">
                <span>Total</span>
            </div>
            <div class="col-lg-6 text-right">
                <span style="margin-right: 13px;" for="subtotal">${rupiahJS(row.subtotals)}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <span style="margin-left: 15px;">Discount</span>
            </div>
            <div class="col-lg-6 text-right">
                <span style="margin-right: 13px;" for="discount">${rupiahJS(row.discounts)}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <span style="margin-left: 15px;">Service Charge</span>
            </div>
            <div class="col-lg-6 text-right">
                <span style="margin-right: 13px;" for="service">${rupiahJS(row.services)}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <span style="margin-left: 15px;">Tax</span>
            </div>
            <div class="col-lg-6 text-right">
                <span style="margin-right: 13px;" for="tax">${rupiahJS(row.taxes)}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <span>Grand Total</span>
            </div>
            <div class="col-lg-6 text-right">
                <span style="margin-right: 13px;" for="total">${rupiahJS(row.totals)}</span>
            </div>
        </div>
        <hr style="margin-top: 5px; margin-bottom: 10px"/>
    `);
}
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
        let promos = SQL(`select * from pos_menus_promos where outlet_menu_id=? and is_avail_${day.data[0].a}='Y'`, id);
        promos.data.forEach(function (promo) {
            let discount = 0;
            if (promo.discount_amount > 0) {
                discount = promo.discount_amount
            } else {
                discount = menu_price / 100 * promo.discount_percent;
            }
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
    loadOrderSummary();
    El.orderMenu.bootstrapTable('insertRow', {
        index: OrderMenu.length,
        row: {
            "menu_outlet_id": data.id,
            "total_amount": menu_price,
            "order_id": Object.keys(Order)[0],
            "order_qty": qty,
            "id": orderItemId,
            "name": data.name,
            "no": OrderMenu.length + 1,
            "total_amount_": rupiahJS(menu_price)
        }
    });
    El.modalQty.modal('hide');
};
let cashPayment = function () {
    let modal = El.modalCash;
    let total = El.orderTotFood.data('value');
    let discount = El.orderTotDiscount.data('value');
    let service = El.orderTotService.data('value');
    let tax = El.orderTotTax.data('value');
    let grandtotal = El.orderTotSum.data('value');
    let change = 0;
    //
    let btnSubmit = modal.find('#submit');
    let lblChange = modal.find('#change');
    let inputAmount = modal.find('#amount');
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
    let modal = El.modalCard;
    let total = El.orderTotFood.data('value');
    let discount = El.orderTotDiscount.data('value');
    let service = El.orderTotService.data('value');
    let tax = El.orderTotTax.data('value');
    let grandtotal = El.orderTotSum.data('value');
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
    let modal = El.modalCharge2Room;
    let total = El.orderTotFood.data('value');
    let discount = El.orderTotDiscount.data('value');
    let service = El.orderTotService.data('value');
    let tax = El.orderTotTax.data('value');
    let grandtotal = El.orderTotSum.data('value');
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
    let houseGuest = SQL(`select * from v_in_house_guest`);
    selectCustomer.html('<option value="">- Choose -</option>');
    houseGuest.data.forEach(function (e) {
        let el = $(`<option value="${e.folio_id}">[${e.room_type} / ${e.room_no}] - ${e.cust_firt_name} ${e.cust_last_name}</option>`);
        el.data(e);
        selectCustomer.append(el);
    });
    selectCustomer.on('change', function(){
        let val1 = selectCustomer.val();
        data = 0;
        if (val1) {
            data = houseGuest.data.filter(function(e){
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
            note: data.note,
            folio_id: data.folio_id,
            payment_type_id: 16,
            grandtotal: grandtotal,
            payment_amount: grandtotal,
            change_amount: 0
        });
        paymentHasDone(pay);
    });
};
$(document).ready(function () {
    loadMealTime();
    loadClass();
    loadSubClass();
    loadMenu();
    loadOrder();
    loadOrderMenu();
    loadOrderSummary();
    cashPayment();
    cardPayment();
    chargeToRoomPayment();
    App.virtualKeyboard();
});