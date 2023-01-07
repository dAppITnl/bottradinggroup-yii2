<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Usermember]].
 *
 * @see Usermember
 */
class UsermemberQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Usermember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Usermember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
