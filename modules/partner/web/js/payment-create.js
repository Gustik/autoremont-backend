$("#billpayment-days").on('change', function() {
    calcAmount($(this).val())
});

function calcAmount(days) {
    var k = getTariffK();
    $("#amount").html(days * k);
}

function getTariffK() {
    var tariffId = $("#billpayment-tariff_id").val();
    var k = $.grep(tariffs, function(e){ return e.id == tariffId; });
    return k[0].day_cost;
}