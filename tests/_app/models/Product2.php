<?php


namespace kittools\tags\tests\_app\models;


use kittools\tags\components\behaviors\TagBehavior;
use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%product2}}".
 *
 * @property integer $id
 * @property string $title
 */
class Product2 extends ActiveRecord
{
    public $tagList = [];

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%product2}}';
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
            ['tagList', 'required'],
            ['tagList', 'each', 'rule' => ['string'], 'message' => 'Tags are filled incorrectly.'],
        ];
    }

    public function behaviors()
    {
        return [
            'tagBehavior' => [
                'class' => TagBehavior::class,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable(TagEntityRelation::class, ['entity_id' => 'id'], function ($query) {
                $query->andWhere([TagEntityRelation::tableName() . '.entity' => static::class]);
            });
    }
}