<?php

$this->registerJsFile('/js/multidatepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/multidatepicker_base.css');
$this->registerCssFile('/css/multidatepicker_clean.css');

use yii\helpers\Html;
use yii\grid\GridView;
//use app\modules\Backend\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список продаж';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить продажу', ['add'], ['class' => 'btn btn-success']) ?>
    </p>
    <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <p>
                <?= Html::a('Добавить продажу', ['add'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 pull-right">
            Кол-во продаж за сегодня:
            Выручка:
        </div>
    </div>-->

    <div>
        <div id="datepicker-calendar" style="display: none; position: absolute;"></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12',
        'columns' => [
            [
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'attribute' => 'created_at',
                'format' => 'text',
            ],
            [
                'label' => 'Товары',
                'format'=>'raw',
                'contentOptions' => ['class' => 'col-xs-10 col-sm-10 col-md-6 col-lg-6'],
                'value' => function($model){

                    $content = '';
                    
                    foreach ($model->productOfSales as $productItem) {
                        $content .= '<div class="col-md-7 col-lg-7 col-sm-12 col-xs-12">'.$productItem->product->name.'</div>';

                        if($productItem->count > 1){
                             $content .= '<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">'.$productItem->count." шт. x ".$productItem->priceOut.' руб = '. $productItem->priceOut * $productItem->count .' руб.</div>';
                        }else{
                            $content .= '<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">'.$productItem->priceOut.' руб </div>';
                        } 
                        $content .= "</br>";
                    }
                    return $content;
                }
            ],

            [
                'label' => 'Скидка',
                'format'=>'raw',
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'value' =>  function($model){
                    if(!empty($model->discount)){
                        return $model->discount.' руб';
                    }
                    return '';
                }
            ],

            [
                'label' => 'Цена',
                'format'=>'raw',
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'value' =>  function($model){
                    return $model->price.' руб';
                }
            ],

            
        ]
    ]); ?>
    </div>
    <p>
        <?= Html::a('Добавить продажу', ['add'], ['class' => 'btn btn-success']) ?>
    </p>
</div>

<script type="text/javascript">
    $(function () {
        var to = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
        var from = new Date();


        $('#datepicker-calendar').DatePicker({
            inline:true,
            calendars: 1,
            mode: 'range',
            date: [from, to],
            current: new Date(),
            onChange: function(dates,el) {
                // update the range display
                //$('#date_departure_from').val(dates[0].getFullYear()+'-'+ (((dates[0].getMonth()+1) < 10)? "0" : "") + (dates[0].getMonth()+1) + '-'+ (((dates[0].getDate()) < 10)?"0":"") + dates[0].getDate());
                //$('#date_departure_to').val(dates[1].getFullYear()+'-'+ (((dates[1].getMonth()+1) < 10)? "0" : "") + (dates[1].getMonth()+1) + '-'+(((dates[1].getDate()) < 10)?"0":"") + dates[1].getDate());

                if((dates[1].getFullYear() == dates[0].getFullYear()) && (dates[1].getMonth() == dates[0].getMonth()) && (dates[1].getDate() == dates[0].getDate()) )
                {
                    $('input[name="SaleSearch[created_at]"]').val((((dates[0].getDate()) < 10)?"0":"") + (dates[0].getDate()) + '.'+(((dates[0].getMonth()+1) < 10)? "0" : "") + (dates[0].getMonth()+1)+'.'+dates[0].getFullYear());
                }else{
                    $('input[name="SaleSearch[created_at]"]').val(
                        (((dates[0].getDate()) < 10)?"0":"") + (dates[0].getDate()) + '.'+(((dates[0].getMonth()+1) < 10)? "0" : "") + (dates[0].getMonth()+1) + '.' + dates[0].getFullYear() + ' - ' +
                        (((dates[1].getDate()) < 10)?"0":"") + (dates[1].getDate()) + '.'+(((dates[1].getMonth()+1) < 10)? "0" : "") + (dates[1].getMonth()+1) + '.' + dates[1].getFullYear()
                    );
                }

            }


        });

        // show datepicker
        $('input[name="SaleSearch[created_at]"]').bind('click', function(){
            $('#datepicker-calendar').toggle();
            return false;
        });

        // hide datepicker
        document.onclick = function(ev) {
            myDiv = document.getElementById('datepicker-calendar');
            if (ev.target.id != myDiv.id)  {
                myDiv.style.display = 'none';
            }
        }
    });
</script>

