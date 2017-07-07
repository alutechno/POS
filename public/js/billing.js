$(document).ready(function () {
    let now = new Date();
    let outletId = App.outlet.id,
        divBills = $('#bills'),
        search = $('input#search');
    let maxLastWeek = 1000 * 60 * 60 * 24 * 7;
    let limitTime = new Date(now.getTime() - maxLastWeek);
    let limitTimeStr = [
        limitTime.getFullYear(),
        limitTime.getMonth() + 1 > 9 ? limitTime.getMonth() + 1 : '0' + (limitTime.getMonth() + 1),
        limitTime.getDate() > 9 ? limitTime.getDate() : '0' + limitTime.getDate()
    ].join('-');
    let joined = {}, avoided = [], joinedSql = SQL(`
        select a.order_id, a.included_order_id, b.code, b.table_id, c.table_no, b.num_of_cover
        from pos_included_orders a
        join pos_orders b on a.included_order_id = b.id
        join mst_pos_tables c on c.id = b.table_id
        where order_id in (select order_id from pos_payment_detail) and a.created_date > '${limitTimeStr}'
    `);
    joinedSql.data.forEach(function (e) {
        joined[e.order_id] = joined[e.order_id] || {};
        joined[e.order_id][e.included_order_id] = e;
        avoided.push(e.included_order_id);
    });
    let {data} = SQL(`
        select
            a.id, a.code, a.num_of_cover, a.modified_date time,
            a.table_id, d.table_no, a.outlet_id, e.name outlet_name,
            a.waiter_user_id, f.name waiter_user_name, 
            a.status, a.sub_total_amount, a.discount_total_amount,
            a.tax_total_amount, a.due_amount,
            b.id trans_batch_id, b.code trans_batch_code, 
            b.working_shift_id trans_batch_shift_id, b.working_shift_name trans_batch_shift_name,
            b.outlet_id trans_batch_outlet_id, b.outlet_name trans_batch_outlet_name,
            b.user_id trans_batch_user_id, b.user_name trans_batch_user_name,
            c.id closing_batch_id, c.code closing_batch_code, 
            c.working_shift_id closing_batch_shift_id, c.working_shift_name closing_batch_shift_name,
            c.outlet_id closing_batch_outlet_id, c.outlet_name closing_batch_outlet_name,
            c.user_id closing_batch_user_id, c.user_name closing_batch_user_name,
            a.order_notes
        from pos_orders a
        left join (
            select 
                x.id, x.code, x.working_shift_id, y.name working_shift_name,
                x.outlet_id, z.name outlet_name, x.user_id, w.name user_name
            from pos_cashier_transaction x
            join ref_pos_working_shift y on x.working_shift_id = y.id
            join mst_outlet z on x.outlet_id = z.id
            join user w on x.user_id = w.id
        ) b on b.id = a.transc_batch_id
        left join (
            select 
                x.id, x.code, x.working_shift_id, y.name working_shift_name,
                x.outlet_id, z.name outlet_name, x.user_id, w.name user_name
            from pos_cashier_transaction x
            join ref_pos_working_shift y on x.working_shift_id = y.id
            join mst_outlet z on x.outlet_id = z.id
            join user w on x.user_id = w.id
        ) c on c.id = a.closing_batch_id
        left join mst_pos_tables d on d.id = a.table_id
        left join mst_outlet e on e.id = a.outlet_id
        left join user f on f.id = a.waiter_user_id
        where a.status != 0 and a.modified_date > '${limitTimeStr}' and a.outlet_id = ${outletId}
    `);
    data.forEach(function (data) {
        if (avoided.indexOf(data.id) > -1 === false) {
            let join = joined[data.id];
            let time = new Date(data.time);
            let d = time.getDate();
            let m = time.getMonth() + 1;
            let y = time.getFullYear();
            let h = time.getHours();
            let i = time.getMinutes();
            let s = time.getSeconds();
            if (d < 10) d = '0' + d;
            if (m < 10) m = '0' + m;
            if (h < 10) h = '0' + h;
            if (i < 10) i = '0' + i;
            if (s < 10) s = '0' + s;
            //
            if (join) {
                data.id =[data.id].concat(Object.keys(join));
                data.code = [data.code].concat(Object.keys(join).map(function (e) {
                    return join[e].code
                }));
                data.table_id = [data.table_id].concat(Object.keys(join).map(function (e) {
                    return join[e].table_id
                }));
                data.table_no = [data.table_no].concat(Object.keys(join).map(function (e) {
                    return join[e].table_no
                }));
                data.join = join;
            } else {
                data.id = [data.id];
                data.code = [data.code];
                data.table_id = [data.table_id];
                data.table_no = [data.table_no];
            }
            let e = $(`
                <a href="/order/${data.id.join('-')}" data-id="${data.id.join('-').toLowerCase()}" data-code="${data.code.join('-').toLowerCase()}">
                    <div class="col-lg-3 col-sm-6 col-xs-6 bill">
                        <div class="col-lg-12" style="padding: 10px;">
                            <label>${data.code.join(', ')}</label>
                            <div>Table : ${data.table_no.join(', ')}</div>
                            <div>Created by : ${data.trans_batch_user_name}</div>
                            <div>Closed by : ${data.closing_batch_user_name}</div>
                            <div>Closed time : ${y}-${m}-${d} ${h}:${i}:${s}</div>
                        </div>
                    </div>
                </a>
            `);
            divBills.append(e)
        }
    });
    search.on('blur', function () {
        let val = $(this).val();
        if (val) {
            val = val.toLowerCase();
            divBills.find('a').hide();
            $(`a[data-id='${val}']`).show();
            $(`a[data-code*='${val}']`).show();
        } else {
            divBills.find('a').show();
        }
    });
    App.virtualKeyboard();
});