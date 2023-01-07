<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Bot]].
 *
 * @see Bot
 */
class BotQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Bot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Bot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
