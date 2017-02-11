<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\modules\Backend\components\GridViewProductForSaleWidget;

$this->title = "Оформление покупки";
$this->params['breadcrumbs'][] = ['label' => 'Покупки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(['id' => 'formSale']); ?>
    <div class="product-attribute-value-form">
        <div class="form-group">
            <h4>Товары в чеке</h4>
            <table class="table table-bordered table-hover">
                <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Изображение</th>
                <th class="col-xs-8 col-sm-8 col-md-3 col-lg-3">Название</th>
                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Кол-во</th>
                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Цена</th>
                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Итого</th>
                <tbody id="contentReceiptId">
                <tr>
                    <td colspan="4">Нет товаров</td>
                </tr> 
                </tbody>
            </table>
            <div class="row">
                <div class="form-group">
                    <div class="form-inline">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"><img src="/images/scan.jpg" width="100px"></div>

                        <div class="col-xs-3 col-sm-2 col-md-5 col-lg-5">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><input class="form-control" id="articleInput" autofocus placeholder="Отсканируйте артикул"></div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?=Html::button('Искать', ['id' => 'addItem', 'class' => 'btn btn-success']);?></div>
                        </div>

                        <div class=" col-xs-6 col-sm-6 col-md-4 col-lg-4 pull-right">
                            <div class="flex justify discount receipt">
                                <div>
                                    <label class="margin-top-7" for="discountId">Скидка </label>
                                </div>
                                <div>
                                    <input type="text" id="discountId" name="Sale[discount]" class="form-control col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                </div>
                               </div>
                            <div class="flex justify price receipt">
                                <div>
                                    <label class="margin-top-7" >ИТОГО </label>
                                </div>
                                <div>
                                    <input type="text" class="form-control col-xs-6 col-sm-6 col-md-4 col-lg-4 sumPrice" disabled>
                                    <input type="hidden" class="sumPrice" name="Sale[price]">
                                </div>
                            </div>
                            <div class="flex justify money receipt">
                                <div>
                                    <label class="margin-top-7" >Наличные </label>
                                </div>
                                <div>
                                    <input type="text" id="money" class="form-control col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                </div>
                            </div>
                            <div class="flex justify money receipt">
                                <div>
                                    <label class="margin-top-7" >Сдача </label>
                                </div>
                                <div>
                                    <input type="text" id="short_change" disabled class="form-control col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                </div>
                            </div>
                        </div>

                        <div class="price col-xs-12 col-sm-12 col-md-4 col-lg-2 text-right">

                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="pull-right col-xs-3 col-sm-2 col-md-2 col-lg-2"><?=Html::button('Сохранить', ['class' => 'btn btn-primary', 'id' => 'saveButton']);?></div>
            </div>
        </div>

    </div>

<?php ActiveForm::end(); ?>
<script type="text/x-template" id="templateItems">
    <td class="text-center">
        <img src="<%=url_img %>" class="imgItem">
    </td>
    <td>
        <input type="hidden" value="<%=stock_id %>" name="ProductOfSale[<%=this.cid %>][stock_id]">
        <input type="hidden" value="<%=price %>" name="ProductOfSale[<%=this.cid %>][price]">
        <input type="hidden" value="<%=product_id %>" name="ProductOfSale[<%=this.cid %>][product_id]">
        <input type="hidden" value="<%=article %>" name="ProductOfSale[<%=this.cid %>][article]">
        <%=name %>
        <small><?php echo Html::a('[удалить]', '#', ['class' => 'deleteItemFromBasket']);?></small>
    </td>
    <td>
        <div class="">
            <input type="text" value="<%=count %>" <% if(countOnStock == 0) {%>disabled="disabled"<%}%> class="form-control countInput" id="count" style="width: 70px;" name="ProductOfSale[<%=this.cid %>][count]">
            <% if(countOnStock > 0) {%>На складе: <%=countOnStock %><%} else {%> <span class="red">Нет на складе</span> <% }%>
        </div>
    </td>
    <td><%=price %> руб.</td>
    <td><%=(count*price) %> руб.</td>
</script>

<script type="text/x-template" id="templatePrepareItems">
    <td class="text-center">
        <img src="<%=url_img %>" class="imgItem">
    </td>
    <td>
        <%=name %>
        <small><?php echo Html::a('[Выбрать]', '#', ['class' => 'chooseItemFromBasket']);?></small>
    </td>
    <td>
        <div class="">
            <% if(countOnStock > 0) {%>На складе: <%=countOnStock %><%} else {%> <span class="red">Нет на складе</span> <% }%>
        </div>
    </td>
    <td><%=price %> руб.</td>
</script>
<?php
    Modal::begin(['id' => 'modalChooseItem','header' => '<h4>Пересечение по артикулу, выберите товар</h4>','size' => "modal-lg"]);
?>
    <div class="modalBlock">
        <table class="table table-bordered table-hover">
            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Изображение</th>
            <th class="col-xs-8 col-sm-8 col-md-3 col-lg-3">Название</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">На складе</th>
            <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Цена</th> 
            <tbody id="contentChooseReceiptId">
            <tr>
                <td colspan="4">Нет товаров</td>
            </tr>
            </tbody>
        </table>
    </div>


<?php
    Modal::end();
?>
<?php
    $this->registerJsFile('/js/newSallingApp.js?v=11', ['depends' => [\yii\web\JqueryAsset::className()]]);
    $this->registerCssFile('/css/sellAdd.css?v=3');
?>
