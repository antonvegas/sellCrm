<?php

namespace app\modules\Backend\models;

use Yii;

/**
 * This is the model class for table "product_of_purchase".
 *
 * @property integer $id
 * @property integer $purchase_id
 * @property integer $product_id
 * @property integer $count
 * @property integer $price
 *
 * @property Product $product
 * @property Purchase $purchase
 */
class ProductOfPurchase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_of_purchase';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_id', 'product_id', 'count', 'priceIn', 'priceOut','article'], 'required'],
            [['purchase_id', 'product_id', 'count'], 'integer'],
            [['priceIn', 'priceOut'], 'number'], 
            [['article'], 'string'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc 
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_id' => 'Purchase ID',
            'product_id' => 'Product ID',
            'count' => 'Кол-во',
            'article' => 'Артикул',
            'price' => 'Price',
            'priceIn' => 'Цена закупки',
            'priceOut' => 'Цена продажи',
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
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

}
