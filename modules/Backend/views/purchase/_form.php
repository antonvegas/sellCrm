<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\modules\Backend\components\GridViewProductWidget;

$this->title = "Выгрузка товара";

?>
<?php $form = ActiveForm::begin(['id' => 'formPurchase']); ?>
<div class="product-attribute-value-form">
    <div class="form-group">
        <h4>Товары в накладной</h4>
        <table class="table table-bordered table-hover" style="margin-bottom: 80px;">
            <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Артикул</th>
            <th class="col-xs-8 col-sm-8 col-md-8 col-lg-4">Название</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-1">Кол-во</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Цена закупки</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Цена продажи</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-4">Итого</th>
            <tbody id="contentReceiptId"> 
            <tr>
                <td colspan="6">Нет товаров</td>
            </tr>
            </tbody>
        </table>

        <div class="row margin-top-7">

                <div class="pull-left col-lg-3"><?=Html::button('Сохранить', ['class' => 'btn btn-primary', 'style' => 'margin-left:10px;', 'id' => 'saveButton']);?></div>
                <div class="price pull-right col-xs-6 col-sm-6 col-md-4 col-lg-2 text-right"><strong>ИТОГО: </strong><span class="sumPrice">0</span> руб.<input type="hidden" value="" class="sumPrice" name="Purchase[price]"></div>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

    <div style="margin-top: -120px;">
        <div class="row" >
            <form id="formSearch" onsubmit="return false;">
                <div class="pull-left col-lg-12 form-inline"><?=Html::button('Добавить товар в ручную', ['id' => 'openModalButton', 'class' => 'btn btn-success']);?> или по артикулу <input class="form-control span3" style="width: 200px;" type="text" autofocus id="articleSearch"><?=Html::button('Искать', ['id' => 'startSearchProductByArticle', 'class' => 'btn btn-success', 'style' => 'margin-left:10px;']);?></div>
            </form>
        </div>
        <hr>
    </div>

<script type="text/x-template" id="templateItems">
    <td><input type="text" class="form-control articleInput" value="<%=article%>" id="article" name="ProductOfPurchase[<%=this.cid %>][article]"></td>
    <td>
        <input type="hidden" value="<%=product_id %>" name="ProductOfPurchase[<%=this.cid %>][product_id]">
        <%=name %>
        <small><?php echo Html::a('[удалить]', '#', ['class' => 'deleteItemFromBasket']);?></small>
    </td>
    <td><input type="text" value="<%=count %>" class="form-control countInput" id="count" style="width: 70px;" name="ProductOfPurchase[<%=this.cid %>][count]"></td>
    <td><div class="form-inline"><input type="text" value="<%=priceIn %>" class="form-control priceInInput" style="width: 70px;" id="priceIn" name="ProductOfPurchase[<%=this.cid %>][priceIn]"><span> руб.</span></div></td>
    <td><div class="form-inline"><input type="text" value="<%=priceOut %>" class="form-control priceOutInput" style="width: 70px;" id="priceOut" name="ProductOfPurchase[<%=this.cid %>][priceOut]"><span> руб.</span></div></td>
    <td><div id="sum_<%=this.cid %>"><%=(count*priceIn) %> руб.</div></td>
</script>

<?php

Modal::begin(['id' => 'modalChooseItem','header' => '<h4>Выберите товар</h4>','size' => "modal-lg"]);
echo GridViewProductWidget::widget();
Modal::end();
$this->registerJsFile('/js/newPurchaseApp.js?v=31', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>