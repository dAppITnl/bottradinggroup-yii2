<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Cryptoaddress]].
 *
 * @see Cryptoaddress
 */
class CryptoaddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Cryptoaddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Cryptoaddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
