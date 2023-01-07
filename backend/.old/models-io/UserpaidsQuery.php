<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Userpaids]].
 *
 * @see Userpaids
 */
class UserpaidsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Userpaids[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Userpaids|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
