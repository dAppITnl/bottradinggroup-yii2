<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Userpay]].
 *
 * @see Userpay
 */
class UserpayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Userpay[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Userpay|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
