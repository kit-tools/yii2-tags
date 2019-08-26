<?php

namespace kittools\tags\models;

use kittools\tags\models\query\TagWeightQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tag_weight}}".
 *
 * @property int $id
 * @property int $tag_id ID tag
 * @property string $entity The name of the class that uses the tag.
 * @property int $weight Number of uses
 * @property int $clicks Number of clicks by tag
 *
 * @property Tag $tag
 */
class TagWeight extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%tag_weight}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['tag_id', 'weight', 'clicks'], 'integer'],
            [['weight'], 'default', 'value' => 0],
            [['clicks'], 'default', 'value' => 0],
            [['entity'], 'string', 'max' => 60],
            [['tag_id', 'entity'], 'unique', 'targetAttribute' => ['tag_id', 'entity']],
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
            'tag_id' => 'ID tag',
            'entity' => 'The name of the class that uses the tag.',
            'weight' => 'Number of uses',
            'clicks' => 'Number of clicks by tag'
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
     * @return TagWeightQuery the active query used by this AR class.
     */
    public static function find(): TagWeightQuery
    {
        return new TagWeightQuery(get_called_class());
    }
}