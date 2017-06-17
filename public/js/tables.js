var trim = function (str) {
    return str.replace(/\t\t+|\n\n+|\s\s+/g, ' ').trim()
};
var q = {
    table: `
        select * from (
            select
                a.*, b.id as posId, b.num_of_cover guest
            from mst_pos_tables a
            left join pos_orders b
            on a.id=b.table_id and b.status in (0,1)
            where a.outlet_id=${App.outlet.id}
            group by a.id order by b.id DESC
        ) x order by table_no, id
    `,
    newOrder: `
        select * from (
            select
                a.*, b.id as posId, b.num_of_cover guest
            from mst_pos_tables a
            left join pos_orders b
            on a.id=b.table_id and b.status in (0,1)
            where a.outlet_id=${App.outlet.id}
            group by a.id order by b.id DESC
        ) x order by table_no, id
    `
};
$(document).ready(function () {
    let tableNo, tableId, numOfCover,
        userId = App.user.id,
        outletId = App.outlet.id,
        workingShiftId = App.outlet.working_shift_id,
        transBatchId = App.posCashier.id;
    let {data} = SQL(q.table);
    let parent = $('#tables-container');
    let guestModal = $('#myModalGuest');
    data.forEach(function (d) {
        let {id, posId, table_no} = d;
        let href = posId ? `/order/${posId || ''}` : '#myModalGuest';
        let table = $(`
            <a href="${href}" data-toggle="modal">
                <div class="col-lg-2 col-sm-3 col-xs-4 dining-table">
                    <div class="frame">
                        <div class="color">
                            <div class="${posId ? 'used' : 'not-used'}">
                                <h5>${table_no}</h5>
                            </div>
                        </div>
                        <div class="bg"></div>
                    </div>
                </div>
            </a>
        `);
        table.data({ id : id, no: table_no });
        table.on('click', function () {
            if (!posId) {
                tableId = table.data('id');
                tableNo = table.data('no');
                $('#myModalLabel span').html(tableNo);
            }
        });
        parent.append(table);
        table.find('.frame').height(table.find('.dining-table').width() - 4 + 'px');
    });
    if (App.posCashier.begin_saldo <= 0) {
        let prompt = $('#myModalBeginSaldo');
        prompt.modal({backdrop: 'static', keyboard: false});
        prompt.modal('show');
        prompt.find('button').on('click', function(){
            let el = prompt.find('input');
            let saldo = el.data('value');
            let req = SQL(`UPDATE pos_cashier_transaction SET begin_saldo=? WHERE id=?`, [saldo, App.posCashier.id]);
            if (!req.error) {
                prompt.modal('hide');
            }
        })
    }
    guestModal.find('button#submit').on('click', function(){
        //todo: make this transaction like!
        let count = SQL('select count(id)+1 as no_bill from pos_orders where outlet_id=?', outletId);
        let code = "CHK-" + count.data[0].no_bill;
        numOfCover = guestModal.find('input').data('value');
        let init = SQL('INSERT pos_orders SET ?', {
            code: code,
            transc_batch_id: transBatchId,
            outlet_id: outletId,
            table_id: tableId,
            num_of_cover: parseInt(numOfCover),
            sub_total_amount: 0,
            discount_total_amount: 0,
            tax_total_amount: 0,
            due_amount: 0,
            segment_id: 1,
            status: 0,
            waiter_user_id: userId,
            created_by: userId
        });
        if (!init.error) {
            let lastId = init.data.insertId;
            let taxes = SQL(`select b.* from pos_outlet_tax a left join mst_pos_taxes b on b.id = a.pos_tax_id where outlet_id = ?`, outletId);
            if (!taxes.error) {
                let result = [];
                taxes.data.forEach(function (tax) {
                    let orderTax = SQL('insert into pos_order_taxes set ?', {
                        order_id : lastId,
                        tax_id : tax.id,
                        tax_percent : tax.tax_percent,
                        tax_amount : 0,
                        created_by : userId
                    });
                    if (!orderTax.error) result.push(1);
                    else result.push(0);
                });
                if (result.indexOf(0) < 0) {
                    window.location.href = '/order/' + lastId
                }
            }
        }
    });
    App.virtualKeyboard();
});