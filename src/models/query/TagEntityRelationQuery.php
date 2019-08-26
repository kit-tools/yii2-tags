<?php

namespace kittools\tags\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\kittools\tags\models\TagEntityRelation]].
 *
 * @see \kittools\tags\models\TagEntityRelation
 */
class TagEntityRelationQuery extends ActiveQuery
{
    /**
     * @param int $tagId
     * @return TagEntityRelationQuery
     */
    public function byTagId(int $tagId): TagEntityRelationQuery
    {
        return $this->andWhere('tag_id=:tag_id', [':tag_id' => $tagId]);
    }

    /**
     * @param string $class
     * @return TagEntityRelationQuery
     */
    public function byEntity(string $class): TagEntityRelationQuery
    {
        return $this->andWhere('entity=:entity', [':entity' => $class]);
    }

    /**
     * @param int $entityId
     * @return TagEntityRelationQuery
     */
    public function byEntityId(int $entityId): TagEntityRelationQuery
    {
        return $this->andWhere('entity_id=:entity_id', [':entity_id' => $entityId]);
    }

    /**
     * @param string $entity
     * @param int $tagId
     * @return TagEntityRelationQuery
     */
    public function weightTagForEntity(string $entity, int $tagId): TagEntityRelationQuery
    {
        return $this
            ->select('COUNT(id)')
            ->andWhere('entity=:entity', [':entity' => $entity])
            ->andWhere('tag_id=:tag_id', [':tag_id' => $tagId]);
    }
}
