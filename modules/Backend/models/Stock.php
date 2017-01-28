<?php

namespace app\modules\Backend\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property integer $id
 * @property string $article
 * @property integer $product_id
 * @property integer $count
 * @property double $priceIn
 * @property double $priceOut
 *
 * @property ProductOfSale[] $productOfSales
 * @property Product $product
 */
class Stock extends \yii\db\ActiveRecord
{
    public $totalOfCount;
    public $categoryName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'count', 'priceIn', 'priceOut'], 'required'],
            [['product_id', 'count'], 'integer'],
            [['priceIn', 'priceOut'], 'number'],

            [['article'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Артикул',
            'product_id' => 'Товар',
            'count' => 'Кол-во',
            'priceIn' => 'Цена закупки',
            'priceOut' => 'Цена продажи',
            'categoryId' => 'Категория',
            'productName' => 'Название',
            'totalOfCount' => 'Общее кол-во',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOfSales()
    {
        return $this->hasMany(ProductOfSale::className(), ['stock_id' => 'id']);
    }
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getProductName() {
        return $this->product->name;
    }

    public function getCategoryName() {
        return $this->product->category_id;
    }

    /*
     *  The method is using when is new purchase for working with stock
     * */
    public function updateOrCreateItemOfStock($options){

        $model = Stock::findOne(
            [
                'article' => $options['article'],
                'priceOut' => $options['priceOut'],
                'product_id' => $options['product_id']
            ]
        );

        if(!empty($model)){

            $newValueOfCount = ($model->count + $options['count']);
            $model->count = $newValueOfCount;

        }else{
            $model = new Stock();

            $model->product_id = $options['product_id'];
            $model->count = $options['count'];
            $model->priceIn = $options['priceIn'];
            $model->priceOut = $options['priceOut'];
            $model->article = $options['article'];

        }

        return $model->save();
    }

    /*
     *  The method for reducing of count of items on stock
     * */
    public function reduceOfCountOfItemById($id, $count){
        $model = Stock::findOne($id);

        $newValueOfCount = ($model->count - $count);
        $model->count = $newValueOfCount < 0 ? 0 : $newValueOfCount;

        return $model->save();
    }

}
