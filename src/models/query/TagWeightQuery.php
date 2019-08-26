<?php

namespace kittools\tags\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\kittools\tags\models\TagWeight]].
 *
 * @see \kittools\tags\models\TagWeight
 */
class TagWeightQuery extends ActiveQuery
{
    /**
     * @param string $entity
     * @param int $tagId
     * @return TagWeightQuery
     */
    public function byEntityAndTagId(string $entity, int $tagId): TagWeightQuery
    {
        return $this
            ->andWhere('entity=:entity', [':entity' => $entity])
            ->andWhere('tag_id=:tag_id', [':tag_id' => $tagId]);
    }
}