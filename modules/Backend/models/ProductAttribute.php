<?php

namespace app\modules\Backend\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ProductAttributeValue[] $productAttributeValues
 * @property Product[] $products
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_attribute_value', ['attribute_id' => 'id']);
    }
}
