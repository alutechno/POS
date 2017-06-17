$('.menu-bg').height($('.menu-bg').parent().width());
let Orders, OrderMenus;
let orderIds = window.location.pathname.split('/')[2].replace(/\-/g, ',');
let rupiahJS = function (val) {
    return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
    .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
};
let loadOrder = function () {
    let parent = $('#orders');
    let orders = SQL(`
        select a.id, a.code,b.table_no from pos_orders a,mst_pos_tables b
        where a.table_id=b.id
        and a.outlet_id=b.outlet_id and a.id in(${orderIds})
    `);
    if (!orders.error) {
        Orders = {};
        parent.html('<code style="margin-right: 5px">Order Number / Table</code>');
        orders.data.forEach(function(e){
            Orders[e.id] = e;
            parent.append(`
                <label class="badge btn-primary" style="font-weight: lighter; margin-right: 5px">
                    ${e.code} / ${e.table_no}
                </label>
            `)
        });
        loadOrderMenu();
    }

};
let loadOrderMenu = function () {
    let parent = $('#order-menus');
    let totFood = $('#order-tot-food');
    let totDiscount = $('#order-tot-discount');
    let totService = $('#order-tot-service');
    let totTax = $('#order-tot-tax');
    let totSum = $('#order-tot-sum');
    let menus = SQL(`
        select
            a.menu_id, sum(a.amount) as amount, a.order_no, sum(a.qty) as qty,
            a.id, sum(a.tax) tax, sum(a.service) service, b.name, 
            @rownum := @rownum + 1 as no, format(sum(a.amount),2) as amount_
        from pos_outlet_order_detil a
        join inv_outlet_menus b on b.id = a.menu_id
        cross join (select @rownum := 0) r
        where order_no in (${
            Object.keys(Orders).map(function(i){
                return `'${Orders[i].code}'`
            }).join()
        })
        group by order_no, menu_id
        order by no
    `);

    OrderMenus = [];
    if (!menus.error) {
        let tax = 0, amount = 0, discount = 0, service = 0;

        OrderMenus = menus.data.map(function (e) {
            e.amount_ = rupiahJS(e.amount);
            amount += e.amount;
            service += e.service;
            tax += e.tax;
            return e
        });

        totFood.html(rupiahJS(amount));
        totDiscount.html(rupiahJS(discount));
        totService.html(rupiahJS(service));
        totTax.html(rupiahJS(tax));
        totSum.html(rupiahJS(amount + tax - discount + service));
    }
    parent.bootstrapTable('load', OrderMenus);

};
$(document).ready(function () {
    loadOrder();
});