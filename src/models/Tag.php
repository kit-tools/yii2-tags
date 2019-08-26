<?php

namespace kittools\tags\models;

use kittools\tags\models\query\TagQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property string $title
 */
class Tag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 30],
            [['title'], 'unique'],
            [['title'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Tag name',
        ];
    }

    /**
     * @inheritdoc
     * @return TagQuery the active query used by this AR class.
     */
    public static function find(): TagQuery
    {
        return new TagQuery(get_called_class());
    }

    /**
     * @return ActiveQuery
     */
    public function getTagEntityRelations(): ActiveQuery
    {
        return $this->hasMany(TagEntityRelation::class, ['tag_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTagWeights(): ActiveQuery
    {
        return $this->hasMany(TagWeight::class, ['tag_id' => 'id']);
    }
}
