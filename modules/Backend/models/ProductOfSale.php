<?php

namespace app\modules\Backend\models;

use Yii;

/**
 * This is the model class for table "product_of_sale".
 *
 * @property integer $id
 * @property integer $sale_id
 * @property integer $product_id
 * @property integer $count
 *
 * @property Product $product
 * @property Sale $sale
 */
class ProductOfSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_of_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_id', 'product_id', 'stock_id', 'count', 'article'], 'required'],
            [['sale_id', 'product_id','stock_id', 'count'], 'integer'],
            [['price'], 'number'],  
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['sale_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sale::className(), 'targetAttribute' => ['sale_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_id' => 'Sale ID',
            'product_id' => 'Product ID',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::className(), ['id' => 'sale_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceOut()
    {
        $result = Stock::findOne(['id' => $this->stock_id]);
        return $result->priceOut;
    }

}
