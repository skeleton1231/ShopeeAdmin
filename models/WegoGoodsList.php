<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wego_goods_list".
 *
 * @property int $id
 * @property string $title
 * @property string|null $title_en
 * @property string $goods_id
 * @property string $shop_id
 * @property string|null $cate
 * @property string|null $category_id
 * @property int $price
 * @property string $formats
 * @property string $imgsSrc
 * @property string $time_stamp
 * @property int $is_translated
 */
class WegoGoodsList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wego_goods_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'goods_id', 'shop_id', 'formats', 'imgsSrc', 'time_stamp'], 'required'],
            [['title', 'formats', 'imgsSrc'], 'string'],
            [['price', 'is_translated'], 'integer'],
            [['title_en', 'category_id'], 'string', 'max' => 1024],
            [['goods_id', 'shop_id', 'cate'], 'string', 'max' => 100],
            [['time_stamp'], 'string', 'max' => 20],
            [['goods_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_en' => 'Title En',
            'goods_id' => 'Goods ID',
            'shop_id' => 'Shop ID',
            'cate' => 'Cate',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'formats' => 'Formats',
            'imgsSrc' => 'Imgs Src',
            'time_stamp' => 'Time Stamp',
            'is_translated' => 'Is Translated',
        ];
    }
}
