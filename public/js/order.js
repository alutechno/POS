let Menu, MealTime, MenuClass, MenuSubClass, Order, OrderMenu,
    orderIds = window.location.pathname.split('/')[2].replace(/\-/g, ','),
    El = {
        menu: $('div#menu'),
        order: $('div#order'),
        mealTime: $('label#meal-time'),
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
    let mealTime = SQL(`select * from ref_meal_time where now() BETWEEN start_time and end_time limit 1`);
    MealTime = {};
    if (!mealTime.error) {
        MealTime = mealTime.data[0];
        El.mealTime.html(MealTime.name)
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
        let query = App.mealTimeMenu ? ` and meal_time_id=${App.mealTimeMenu}` : ''
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
            El.menu.find('.menu-item').on('click', function(){
                console.log($(this).data())
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
            a.menu_id, sum(a.amount) as amount, a.order_no, sum(a.qty) as qty,
            a.id, sum(a.tax) tax, sum(a.service) service, b.name, 
            @rownum := @rownum + 1 as no, format(sum(a.amount),2) as amount_
        from pos_outlet_order_detil a
        join inv_outlet_menus b on b.id = a.menu_id
        cross join (select @rownum := 0) r
        where order_no in (${
            Object.keys(Order).map(function (i) {
                return `'${Order[i].code}'`
            }).join()
        })
        group by order_no, menu_id
        order by no
    `);

    OrderMenu = [];
    if (!orderMenu.error) {
        let tax = 0, amount = 0, discount = 0, service = 0;

        OrderMenu = orderMenu.data.map(function (e) {
            e.amount_ = rupiahJS(e.amount);
            amount += e.amount;
            service += e.service;
            tax += e.tax;
            return e
        });

        El.orderTotFood.html(rupiahJS(amount));
        El.orderTotDiscount.html(rupiahJS(discount));
        El.orderTotService.html(rupiahJS(service));
        El.orderTotTax.html(rupiahJS(tax));
        El.orderTotSum.html(rupiahJS(amount + tax - discount + service));
    }
    El.orderMenu.bootstrapTable('load', OrderMenu);

};
$(document).ready(function () {
    loadMealTime();
    loadClass();
    loadSubClass();
    loadMenu();
    loadOrder();
    loadOrderMenu();

    El.menuFinder.on('blur', function () {
        loadMenu({class: El.menuClass.val(), subClass: El.menuSubClass.val(), name: El.menuFinder.val()})
    })
});