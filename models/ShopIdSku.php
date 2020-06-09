<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_id_sku".
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property int $product_id
 */
class ShopIdSku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_id_sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'sku', 'product_id'], 'required'],
            [['id', 'product_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['sku'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sku' => 'Sku',
            'product_id' => 'Product ID',
        ];
    }
}
