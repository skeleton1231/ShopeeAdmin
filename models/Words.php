<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "words".
 *
 * @property string $word
 * @property string|null $type
 * @property int $id
 * @property int $num
 * @property string|null $convert
 */
class Words extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'words';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word'], 'required'],
            [['num'], 'integer'],
            [['word', 'convert'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'word' => 'Word',
            'type' => 'Type',
            'id' => 'ID',
            'num' => 'Num',
            'convert' => 'Convert',
        ];
    }
}
