<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Membership]].
 *
 * @see Membership
 */
class MembershipQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Membership[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Membership|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
