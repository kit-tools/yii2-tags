<?php

namespace kittools\tags\components\behaviors;

use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * TagBehavior automatically saves tags added when creating / editing a model.
 *  * When you delete a model, it removes the associated tags.
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => TagBehavior::class,
 *         ],
 *     ];
 * }
 * ```
 * @package kittools\tags\components\behaviors
 */
class TagBehavior extends Behavior
{
    public $tagModelClass = Tag::class;

    public $tagEntityRelationClass = TagEntityRelation::class;

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'tagAssociations',
            ActiveRecord::EVENT_AFTER_UPDATE => 'tagAssociations',
            ActiveRecord::EVENT_AFTER_DELETE => 'removeTagAssociations',
        ];
    }

    /**
     * The method adds new tags, and updates the links to $this->owner.
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function tagAssociations(): void
    {
        $usedTagIds = [];
        if (!empty($this->owner->tagList)) {
            foreach ($this->owner->tagList as $tag) {
                $modelTag = $this->tagModelClass::find()->byTitle($tag)->one();

                if (empty($modelTag)) {
                    $modelTag = new $this->tagModelClass();
                    $modelTag->title = $tag;
                    $modelTag->save();
                }

                $modelTagEntityRelation = $this->tagEntityRelationClass::find()
                    ->byTagId($modelTag->id)
                    ->byEntity(get_class($this->owner))
                    ->byEntityId($this->owner->id)
                    ->one();

                if (empty($modelTagEntityRelation)) {
                    $modelTagEntityRelation = new $this->tagEntityRelationClass();
                    $modelTagEntityRelation->tag_id = $modelTag->id;
                    $modelTagEntityRelation->entity = get_class($this->owner);
                    $modelTagEntityRelation->entity_id = $this->owner->id;
                    $modelTagEntityRelation->save();
                }

                $usedTagIds[] = $modelTag->id;
            }
        }

        if (($modelsTagRelation = $this->getModelsTagRelation()) !== null) {
            foreach ($modelsTagRelation as $modelTagRelation) {
                if (!in_array($modelTagRelation->tag_id, $usedTagIds)) {
                    $modelTagRelation->delete();
                }
            }
        }
    }

    /**
     * The method searches for related tags with $this->owner and returns the TagEntityRelation models.
     *
     * @return array|TagEntityRelation[]
     */
    protected function getModelsTagRelation()
    {
        return $this->tagEntityRelationClass::find()
            ->byEntity(get_class($this->owner))
            ->byEntityId($this->owner->id)
            ->all();
    }

    /**
     * When deleting $this->owner, removes all links with tags and recounts the weight of the togens.
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeTagAssociations(): void
    {
        $modelsTagRelation = $this->getModelsTagRelation();
        foreach ($modelsTagRelation as $modelTagRelation) {
            $modelTagRelation->delete();
        }
    }
}