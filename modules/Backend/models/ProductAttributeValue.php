<?php

namespace app\modules\Backend\models;

use Yii;

/**
 * This is the model class for table "product_attribute_value".
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property string $value
 *
 * @property ProductAttribute $attribute
 * @property Product $product
 */
class ProductAttributeValue extends \yii\db\ActiveRecord
{

    const SCENARIO_TABULAR = 'tabular';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'attribute_id'], 'required'],
            [['value'], 'required', 'except' => self::SCENARIO_TABULAR ],
            [['product_id', 'attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Товар',
            'attribute_id' => 'Название атрибута',
            'value' => 'Значение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */ 
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
