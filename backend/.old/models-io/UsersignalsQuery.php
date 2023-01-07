<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Usersignals]].
 *
 * @see Usersignals
 */
class UsersignalsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Usersignals[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Usersignals|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
