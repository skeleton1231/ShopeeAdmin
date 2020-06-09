<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produt_json".
 *
 * @property string $name
 * @property string $json
 */
class ProdutJson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produt_json';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'json'], 'required'],
            [['json'], 'string'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'json' => 'Json',
        ];
    }
}
