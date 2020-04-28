<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%seller_accounts}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $site
 * @property string $platform
 * @property string $type
 */
class SellerAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%seller_accounts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'site'], 'required'],
            [['username', 'password', 'platform', 'type'], 'string', 'max' => 20],
            [['site'], 'string', 'max' => 5],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'site' => 'Site',
            'platform' => 'Platform',
            'type' => 'Type',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SellerAccountsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SellerAccountsQuery(get_called_class());
    }
}
