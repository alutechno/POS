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
        orderTotSum: $('td#order-tot-sum')
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
                addOrderMenu($(this).data())
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
            a.outlet_menu_id, sum(a.price_amount) price_amount, a.order_id, 
            sum(a.order_qty) as order_qty, a.id, b.name, format(sum(a.price_amount),2) as price_amount_
        from pos_orders_line_item a
        join inv_outlet_menus b on b.id = a.outlet_menu_id
        cross join (select @rownum := 0) r
        where order_id in (${Object.keys(Order).join()})
        group by order_id, outlet_menu_id
        order by no
    `);
    OrderMenu = [];
    if (!orderMenu.error) {
        OrderMenu = orderMenu.data;
    }
    El.orderMenu.bootstrapTable('load', OrderMenu);

};
let loadOrderSummary = function () {
    //El.orderTotFood.html(rupiahJS(amount));
    //El.orderTotDiscount.html(rupiahJS(discount));
    //El.orderTotService.html(rupiahJS(service));
    //El.orderTotTax.html(rupiahJS(tax));
    //El.orderTotSum.html(rupiahJS(amount + tax - discount + service));
}
let addOrderMenu = function (data) {
    console.log(data)
    let {id, menu_price, menu_class_id, outlet_id} = data;
    let qty = 1, totalDiscount = 0, nettPrice = menu_price;
    let order = Order[Object.keys(Order)[0]];
    let newLineItem = {
        order_id: order.id,
        menu_class_id: menu_class_id,
        outlet_menu_id: id,
        serving_status: 0,
        order_qty: qty,
        price_amount: menu_price,
        total_amount: qty * menu_price,
        created_by: App.user.id
    }
    let lineItem = SQL('insert into pos_orders_line_item set ?', newLineItem);
    //todo: if lineItem.error
    let lineItemId = lineItem.data.insertId;

    let day = SQL('select LOWER(DAYNAME(NOW())) a');
    //todo: if day.error

    let promos = SQL(`select * from pos_menus_promos where outlet_menu_id=? and is_avail_${day.data[0].a}='Y'`, id);
    //todo: if promo.error
    promos.data.forEach(function (promo) {
        let discount = 0;
        if (promo.discount_amount > 0) {
            discount = promo.discount_amount
        } else {
            discount = menu_price / 100 * promo.discount_percent;
        }
        totalDiscount += discount;

        let newPatchDiscount = {
            order_line_item_id: lineItemId,
            menu_class_id: menu_class_id,
            outlet_menu_id: id,
            promo_id: promo.id,
            discount_amount: discount,
            created_by: App.user.id
        }

        let patchDiscount = SQL('insert into pos_patched_discount set ?', newPatchDiscount);
        //todo: if patchDiscount.error
    })

    let outletTaxes = SQL('select is_tax_included e from inv_outlet_menus where id = ?', id);
    //todo: if outletTaxes.error
    if (outletTaxes.data[0].e == 'N') {
        let orderTaxes = SQL('select * from pos_order_taxes where order_id = ?', order.id);
        nettPrice = menu_price - totalDiscount;
        //todo: if orderTaxes.error
        orderTaxes.data.forEach(function (orderTax) {
            let taxAmount = orderTax.tax_amount + (nettPrice * orderTax.tax_percent / 100);
            let updateOrderTaxes = SQL('update pos_order_taxes set tax_amount = ? where id = ?', [taxAmount, orderTax.id])
            //todo: if updateOrderTaxes.error
        });
    }
    //todo: ADDITIONAL TAX?
    let taxes = SQL('select sum(tax_amount) tax from pos_order_taxes where order_id=?', order.id)
    //todo: if taxes.error
    let totalTaxes = taxes.data[0].tax;

    let posOrder = SQL('select * from pos_orders where id = ?', order.id);
    //todo: if posOrder.error
    let {sub_total_amount, tax_total_amount, due_amount, discount_total_amount} = posOrder.data[0];
    sub_total_amount += menu_price;
    discount_total_amount += totalDiscount;
    let updatePosOrder = SQL('update pos_orders set sub_total_amount=?, discount_total_amount=?, tax_total_amount=?, due_amount=? where id=?', [
        sub_total_amount,
        discount_total_amount,
        totalTaxes,
        sub_total_amount - discount_total_amount + totalTaxes,
        order.id
    ]);
    El.orderMenu.bootstrapTable('insertRow', {
        index: OrderMenu.length,
        row: {
            "menu_outlet_id": data.id,
            "price_amount": menu_price,
            "order_id": Object.keys(Order)[0],
            "order_qty": qty,
            "id": lineItemId,
            "name": data.name,
            "no": OrderMenu.length + 1,
            "price_amount_": rupiahJS(menu_price)
        }
    });
    loadOrderSummary();
};
$(document).ready(function () {
    loadMealTime();
    loadClass();
    loadSubClass();
    loadMenu();
    loadOrder();
    loadOrderMenu();
    loadOrderSummary();

    El.menuFinder.on('blur', function () {
        loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
    })
});