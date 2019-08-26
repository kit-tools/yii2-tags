<?php


namespace kittools\tags\components\behaviors;


use kittools\tags\models\TagEntityRelation;
use kittools\tags\models\TagWeight;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class TagWeightBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateTagWeight',
            ActiveRecord::EVENT_AFTER_DELETE => 'updateTagWeight',
        ];
    }

    public function updateTagWeight()
    {
        $weight = TagEntityRelation::find()
            ->select('COUNT(id)')
            ->weightTagForEntity($this->owner->entity, $this->owner->tag_id)->scalar();

        $modelTagWeight = TagWeight::find()->byEntityAndTagId($this->owner->entity, $this->owner->tag_id)->one();

        if (empty($weight)) {
            if (!empty($modelTagWeight)) {
                $modelTagWeight->delete();
            }
        } else {
            if (empty($modelTagWeight)) {
                $modelTagWeight = new TagWeight();
                $modelTagWeight->tag_id = $this->owner->tag_id;
                $modelTagWeight->entity = $this->owner->entity;
            }

            $modelTagWeight->weight = $weight;
            $modelTagWeight->save();
        }
    }
}