<?php


namespace kittools\tags\components\behaviors;


use kittools\tags\models\TagEntityRelation;
use kittools\tags\models\TagWeight;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Tag weight update
 * @package kittools\tags\components\behaviors
 */
class TagWeightBehavior extends Behavior
{
    public $tagEntityRelationClass = TagEntityRelation::class;

    public $tagWeightClass = TagWeight::class;

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateTagWeight',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateTagWeight',
            ActiveRecord::EVENT_AFTER_DELETE => 'updateTagWeight',
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateTagWeight()
    {
        $weight = $this->tagEntityRelationClass::find()
            ->select('COUNT(id)')
            ->weightTagForEntity($this->owner->entity, $this->owner->tag_id)->scalar();

        $modelTagWeight = $this->tagWeightClass::find()->byEntityAndTagId($this->owner->entity, $this->owner->tag_id)->one();

        if (empty($weight)) {
            if (!empty($modelTagWeight)) {
                $modelTagWeight->delete();
            }
        } else {
            if (empty($modelTagWeight)) {
                $modelTagWeight = new $this->tagWeightClass();
                $modelTagWeight->tag_id = $this->owner->tag_id;
                $modelTagWeight->entity = $this->owner->entity;
            }

            $modelTagWeight->weight = $weight;
            $modelTagWeight->save();
        }
    }
}