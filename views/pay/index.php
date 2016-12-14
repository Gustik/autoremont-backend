<?php

/* @var $this yii\web\View */
$this->title = 'Оплата подписки';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
    var tariffs = {"year": 30000, "month": 3000, "day": 150};

    var yi = $("input[name='year']");
    yi.TouchSpin({min: 0, max: 5, step: 1 });
    yi.on("touchspin.on.startspin", function() {
        calcSum(tariffs[this.id], this.value)
    });

    var mi = $("input[name='month']");
    mi.TouchSpin({min: 0, max: 12, step: 1 });
    mi.on("touchspin.on.startspin", function() {
        calcSum(tariffs[this.id], this.value)
    });

    var di = $("input[name='day']");
    di.TouchSpin({min: 0, max: 31, step: 1 });
    di.on("touchspin.on.startspin", function() {
        calcSum(tariffs[this.id], this.value)
    });

    $(".tab-item").click(resetSum);

    function calcSum(tariff, count) {
        setSum(tariff * count);
    }

    function setSum(sum) {
        document.getElementById("pay-sum").innerHTML = "" + sum + " руб.";
    }

    function resetSum() {
        var id = $(this).data("id");
        var i = $("input[name='"+ id +"']");
        var count = i[0].value;
        calcSum(tariffs[id], count)
    }
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="site-pay">
    <div class="row">
        <div class="col-md-3 col-md-offset-5">
            <form action="execute" id="payForm">
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                    <input class="form-control" type="text" name='phone' placeholder="Телефон"/>
                </div>

                <div id="tab" class="btn-group pay-buttons" data-toggle="buttons">
                    <a data-id="year" href="#year" class="btn btn-default active tab-item" data-toggle="tab">
                        <input type="radio" name="tariff" value="year" checked/>Годовая</a>
                    <a data-id="month"href="#month" class="btn btn-default tab-item" data-toggle="tab">
                        <input type="radio" name="tariff" value="month"/>Месячная</a>
                    <a data-id="day" href="#day" class="btn btn-default tab-item" data-toggle="tab">
                        <input type="radio" name="tariff" value="day"/>Суточная</a>
                </div>

                <div class="tab-content pay-tabs center-block">
                    <div class="tab-pane active" id="year"><input id="year" type="text" value="0" name="year"></div>
                    <div class="tab-pane" id="month"><input id="month" type="text" value="0" name="month"></div>
                    <div class="tab-pane" id="day"><input id="day" type="text" value="0" name="day"></div>
                </div>

                <div id="pay-sum" class="center-block">
                    0 руб.
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success center-block">Оплатить</button>
                </div>
            </form>
        </div>

    </div>
</div>
