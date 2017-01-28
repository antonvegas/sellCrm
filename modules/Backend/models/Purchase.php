<?php

namespace app\modules\Backend\models;

use app\modules\Backend\models\ProductOfPurchase;
use app\models\Users;
use Yii;


/**
 * This is the model class for table "purchase".
 *
 * @property integer $id
 * @property string $updated_at
 * @property string $created_at
 * @property integer $seller_id
 * @property integer $price
 *
 * @property ProductOfPurchase[] $productOfPurchases
 * @property Users $seller
 */
class Purchase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at', 'seller_id'], 'safe'],
            [['price'], 'number'], 
            //[['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'updated_at' => 'Обновлено',
            'created_at' => 'Дата завоза',
            'seller_id' => 'Продавец',
            'price' => 'Сумма закупки',
            'priceIn' => 'Цена закупки',
            'priceOut' => 'Цена продажи',
            'sellerName' => 'Сотрудник',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOfPurchases()
    {
        return $this->hasMany(ProductOfPurchase::className(), ['purchase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Users::className(), ['id' => 'seller_id']);
    }

    public function getSellerName()
    {
        return $this->seller->name;
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
}
