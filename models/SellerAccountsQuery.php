<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SellerAccounts]].
 *
 * @see SellerAccounts
 */
class SellerAccountsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SellerAccounts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SellerAccounts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
