<?php

namespace app\modules\Backend\models\activeQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\crm\models\ProductOfSale]].
 *
 * @see \app\modules\crm\models\ProductOfSale
 */
class ProductOfSaleActiveQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function withNameOfProducts()
    {
        return $this->leftJoin('product pr','pr.id = product_of_sale.product_id');
    }
    
    
    /**
     * @inheritdoc
     * @return \app\modules\crm\models\ProductOfSale[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\crm\models\ProductOfSale|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
