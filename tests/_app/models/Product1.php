<?php


namespace kittools\tags\tests\_app\models;


use kittools\tags\components\behaviors\TagBehavior;
use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%product1}}".
 *
 * @property integer $id
 * @property string $title
 */
class Product1 extends ActiveRecord
{
    /** @var array */
    public $tagList = [];

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%product1}}';
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
            ['tagList', 'required', 'skipOnEmpty' => false],
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
            ->viaTable(TagEntityRelation::tableName(), ['entity_id' => 'id'], function ($query) {
                $query->andWhere([TagEntityRelation::tableName() . '.entity' => static::class]);
            });
    }
}