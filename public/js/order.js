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
        myModalQty: $('div#myModalQty'),
    };
let rupiahJS = function (val) {
    return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
    .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
};
let loadMealTime = function () {
    let mealTime = SQL(`select * from ref_meal_time where DATE_FORMAT(now(), '%H:%i:%s') BETWEEN start_time and end_time`);
    MealTime = {};
    El.mealTime.html('<code style="margin-right: 5px">Meal Time</code>');
    if (!mealTime.error) {
        mealTime.data.forEach(function(e){
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
                    <div class="col-lg-3 col-sm-4 col-xs-6 menu-item" 
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
                El.myModalQty.find('button#submit').removeAttr('disabled');
                El.myModalQty.find('h4').html($(this).data('name'));
                El.myModalQty.modal('show');
                El.myModalQty.data($(this).data());
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
    El.myModalQty.modal('hide');
};
$(document).ready(function () {
    loadMealTime();
    loadClass();
    loadSubClass();
    loadMenu();
    loadOrder();
    loadOrderMenu();
    loadOrderSummary();
    El.myModalQty.find('button#submit').on('click', function () {
        let qty = El.myModalQty.find('input#qty').data('value');
        let item = El.myModalQty.data();
        if (parseInt(qty) > 0) {
            El.myModalQty.find('button#submit').attr('disabled', 1);
            addOrderMenu(item, parseInt(qty));
        }
    });
    El.menuFinder.on('blur', function () {
        loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
    });
    App.virtualKeyboard();
});