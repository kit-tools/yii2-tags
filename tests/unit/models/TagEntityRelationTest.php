<?php

namespace kittools\tags\tests\unit\models;

use kittools\tags\components\behaviors\TagWeightBehavior;
use kittools\tags\models\query\TagEntityRelationQuery;
use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
use stdClass;
use yii\db\ActiveQuery;

class TagEntityRelationTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testBehaviors()
    {
        $behaviors = [
            'tagWeightBehavior' => [
                'class' => TagWeightBehavior::class,
            ],
        ];

        $this->tester->assertSame($behaviors, (new TagEntityRelation())->behaviors());
    }

    public function testTableName()
    {
        $this->tester->assertSame('{{%tag_entity_relation}}', TagEntityRelation::tableName());
    }

    public function testRules()
    {
        $rules = [
            [['tag_id', 'entity_id'], 'integer'],
            [['entity'], 'string', 'max' => 60],
            [['tag_id', 'entity', 'entity_id'], 'unique', 'targetAttribute' => ['tag_id', 'entity', 'entity_id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
        $tag = new Tag();
        $tag->title = 'test tag';
        $tag->save();

        $tagEntityRelation = new TagEntityRelation();
        $tagEntityRelation->link('tag', $tag);
        $tagEntityRelation->entity = stdClass::class;
        $tagEntityRelation->entity_id = 123;

        $this->tester->assertTrue($tagEntityRelation->save());

        $tagEntityRelation = new TagEntityRelation();
        $tagEntityRelation->link('tag', $tag);
        $tagEntityRelation->entity = stdClass::class;
        $tagEntityRelation->entity_id = 123;

        $this->tester->assertFalse($tagEntityRelation->save());
    }

    public function testAttributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'tag_id' => 'ID тега',
            'entity' => 'The name of the class using the tag',
            'entity_id' => 'The primary key of the class using the tag',
        ];
        $this->tester->assertSame($labels, (new TagEntityRelation())->attributeLabels());
    }

    public function testGetTag()
    {
        //return $this->hasOne(Tag::class, ['id' => 'tag_id']);
        $this->tester->assertInstanceOf(ActiveQuery::class, (new TagEntityRelation())->getTag());
    }

    public function testFind()
    {
        $this->tester->assertInstanceOf(TagEntityRelationQuery::class, TagEntityRelation::find());
    }
}