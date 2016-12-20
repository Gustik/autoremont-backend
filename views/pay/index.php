<?php

/* @var $tariffs array */
/* @var $this yii\web\View */

$this->title = 'Оплата подписки';
$this->params['breadcrumbs'][] = $this->title;

$t = json_encode($tariffs);

$script = <<< JS
    var tariffs = $t;

    setInfo("Стоимость суток: " + tariffs[1] + ' руб.');

    var di = $("input[name='days']");
    di.TouchSpin({min: 0, max: 1000, step: 1 });
    di.on("touchspin.on.startspin", function() {
        calcSum(this.value)
    });

    function calcSum(count) {
        var dayCost = 0;

        for (var key in tariffs){
            var daysCount = parseInt(key);
            var cost = tariffs[key];

            if(count >= daysCount) {
                dayCost = cost;
                setInfo("Стоимость суток: " + dayCost + ' руб.');
            }
        }
        setSum(dayCost * count);
    }

    function setSum(sum) {
        document.getElementById("pay-sum").innerHTML = "" + sum + " руб.";
    }

    function setInfo(info) {
        document.getElementById("pay-info").innerHTML = info;
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

                <div class="center-block">
                    <div><input id="day" type="text" value="0" name="days"></div>
                </div>

                <div id="pay-info" class="alert alert-info">

                </div>

                <div>
                    <div class="pay-sum-label">Сумма к оплате:</div>
                    <div id="pay-sum">
                        0 руб.
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success center-block">Далее</button>
                </div>
            </form>
        </div>

    </div>
</div>
