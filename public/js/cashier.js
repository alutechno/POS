$(document).ready(function () {
    let userId = App.user.id, transBatchId = App.posCashier.id;
    let shiftModal = $('#myModalShift');
    let btnCloseBalance = shiftModal.find('#submit');
    btnCloseBalance.hide();
    if (App.role.reports) {
        btnCloseBalance.show();
        shiftModal.find('#submit').on('click', function(){
            let getDate = SQL('select NOW() now');
            let datetime = getDate.data[0].now;
            let money = SQL(`
                select
                    sum(c.payment_amount-c.change_amount) uang
                from pos_orders a
                join pos_cashier_transaction b on b.id = a.transc_batch_id
                join pos_payment_detail c on c.order_id = a.id
                where c.payment_type_id in (select id from ref_payment_method where name like '%cash%')
                and b.id = ?
            `, transBatchId);
            let updatePosCashier = SQL(
                `update pos_cashier_transaction set end_time=?,closing_saldo=?,modified_by=?,modified_date=? where id=?`,
                [datetime, money.data[0].uang, userId, datetime, transBatchId]
            );
            if (!updatePosCashier.error) {
                $.ajax({
                    method: 'GET',
                    url: '/printCashierReport',
                    data: {
                        posCashierId: transBatchId
                    },
                    complete: function (xhr, is) {
                        if (is == 'success') {
                            if (xhr.responseJSON) {
                                if (!xhr.responseJSON.error) {
                                    setTimeout(function () {
                                        window.location.href = '/'
                                    }, 3000);
                                    console.info(xhr.responseJSON.message);
                                } else console.error(xhr.responseJSON.message);
                            } else console.error(xhr.response);
                        } else console.error('Server down!');
                    }
                });
            }
        });
    }
});