<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Pricelist]].
 *
 * @see Pricelist
 */
class PricelistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Pricelist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Pricelist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
