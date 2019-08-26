<?php

namespace kittools\tags\models;

use kittools\tags\components\behaviors\TagWeightBehavior;
use kittools\tags\models\query\TagEntityRelationQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tag_entity_relation}}".
 *
 * @property int $id
 * @property int $tag_id ID tag
 * @property string $entity The name of the class using the tag
 * @property string $entity_id The primary key of the class using the tag
 *
 * @property Tag $tag
 */
class TagEntityRelation extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'tagWeightBehavior' => [
                'class' => TagWeightBehavior::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%tag_entity_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['tag_id', 'entity_id'], 'integer'],
            [['entity'], 'string', 'max' => 60],
            [['tag_id', 'entity', 'entity_id'], 'unique', 'targetAttribute' => ['tag_id', 'entity', 'entity_id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'tag_id' => 'ID тега',
            'entity' => 'The name of the class using the tag',
            'entity_id' => 'The primary key of the class using the tag',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    /**
     * @inheritdoc
     * @return TagEntityRelationQuery the active query used by this AR class.
     */
    public static function find(): TagEntityRelationQuery
    {
        return new TagEntityRelationQuery(get_called_class());
    }
}
