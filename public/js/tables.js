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
$(function () {
    let {data} = SQL(q.table);
    let parent = $('#tables-container');
    let btn = $('button#submit');
    data.forEach(function (d) {
        var {id, posId, table_no} = d;
        var href = posId ? `/order/${posId || ''}` : '#myModalguest';
        var table = $(`
            <a href="${href}" data-toggle="modal">
                <div class="col-xs-2 dinein-table">
                    <div class="${posId ? 'used' : 'notused'}">
                        <div class="text-center table-number">${table_no}</div>
                    </div>
                </div>
            </a>
        `);
        table.on('click', function () {
            if (!posId) $('#myModalLabel span').html(table_no);
        });
        parent.append(table);
    });
    btn.on('click', function(){
        //todo: input new order
    })
});