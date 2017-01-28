<?php

namespace app\modules\Backend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $description 
 * @property integer $active
 * @property integer $count
 *
 * @property Category $category
 * @property ProductOfPurchase[] $productOfPurchases
 * @property ProductOfSale[] $productOfSales
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $price;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'active'], 'integer'],
            [['name'], 'required'], 
            [['description', 'url_image'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'active' => 'Активность',
            'count' => 'Кол-во',
            'imageFile' => 'Изображение',
        ];
    }

    public function beforeSave($insert){


        if (parent::beforeSave($insert)) {

            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

            if (!empty($this->imageFile)) {

                $this->url_image =  time(). '.' . $this->imageFile->extension;

                if (!empty($this->oldAttributes['url_image']) && file_exists(Yii::$app->basePath .'/web'. Yii::$app->params['productImagePath'] . $this->oldAttributes['url_image'])) {
                    unlink( Yii::$app->basePath .'/web'. Yii::$app->params['productImagePath'] . $this->oldAttributes['url_image'] );
                }

                $this->imageFile->saveAs(Yii::$app->basePath .'/web'. Yii::$app->params['productImagePath'] . $this->url_image);

            }

            return true;

        }
        return false;
    }

    public function beforeDelete(){


        if (parent::beforeDelete()) {

            if (!empty($this->url_image)) {

                if (file_exists(Yii::$app->basePath .'/web'. Yii::$app->params['productImagePath'] . $this->url_image)) {
                    unlink( Yii::$app->basePath .'/web'. Yii::$app->params['productImagePath'] . $this->url_image );
                }

            }

            return true;

        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOfPurchases()
    {
        return $this->hasMany(ProductOfPurchase::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOfSales()
    {
        return $this->hasMany(ProductOfSale::className(), ['product_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributeValue()
    {
        return $this->hasMany(ProductAttributeValue::className(), ['product_id' => 'id']);
    }
        
}
