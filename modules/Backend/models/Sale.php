<?php

namespace app\modules\Backend\models;

use Yii;
use app\models\Users; 

/**
 * This is the model class for table "sale".
 *
 * @property integer $id
 * @property string $updated_at
 * @property string $created_at
 * @property integer $seller_id
 * @property integer $discount
 * @property integer $price
 *
 * @property ProductOfSale[] $productOfSales
 * @property Users $seller
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at'], 'safe'],
            [['seller_id', 'discount'], 'integer'],
            [['price'], 'number'],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Дата продажи',
            'seller_id' => 'Продавец',
            'discount' => 'Скидка',
            'price' => 'Итого',
        ];
    }

    public function beforeSave($insert){

        if (parent::beforeSave($insert)) {

            if($this->isNewRecord){
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->seller_id = Yii::$app->user->id;

            return true;
        }
        return false;
    } 


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOfSales()
    {
        return $this->hasMany(ProductOfSale::className(), ['sale_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Users::className(), ['id' => 'seller_id']);
    }


}


