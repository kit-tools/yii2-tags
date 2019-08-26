<?php

namespace kittools\tags\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\kittools\tags\models\Tag]].
 *
 * @see \kittools\tags\models\Tag
 */
class TagQuery extends ActiveQuery
{
    /**
     * @param string $title
     * @return TagQuery
     */
    public function byTitle(string $title): TagQuery
    {
        return $this->andWhere('title=:title', [':title' => $title]);
    }
}
