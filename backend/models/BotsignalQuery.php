<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Botsignal]].
 *
 * @see Botsignal
 */
class BotsignalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Botsignal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Botsignal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
