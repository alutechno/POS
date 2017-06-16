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
    let {data} = SQL(q.table);
    let parent = $('#tables-container');
    let btn = $('button#submit');
    data.forEach(function (d) {
        var {id, posId, table_no} = d;
        var href = posId ? `/order/${posId || ''}` : '#myModalGuest';
        var table = $(`
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
        table.on('click', function () {
            if (!posId) $('#myModalLabel span').html(table_no);
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
            console.log(el.data('value'));
            //todo: input new order
            prompt.modal('hide');
        })
    }
    btn.on('click', function(){
        //todo: input new order
    });
    App.virtualKeyboard();
});