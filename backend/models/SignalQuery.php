<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Signal]].
 *
 * @see Signal
 */
class SignalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Signal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Signal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
