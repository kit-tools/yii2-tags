<?php

namespace kittools\tags\tests\unit\models;

use kittools\tags\models\query\TagWeightQuery;
use kittools\tags\models\Tag;
use kittools\tags\models\TagWeight;
use stdClass;
use yii\db\ActiveQuery;

class TagWeightTest extends \Codeception\Test\Unit
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

    public function testTableName()
    {
        $this->tester->assertSame('{{%tag_weight}}', TagWeight::tableName());
    }

    public function testRules()
    {
        $tag = new Tag();
        $tag->title = 'test tag';
        $tag->save();

        $tagWeight = new TagWeight();
        $tagWeight->tag_id = $tag->id;
        $tagWeight->weight = 2;
        $tagWeight->clicks = 3;
        $tagWeight->entity = stdClass::class;
        $tagWeight->save();

        $this->tester->assertFalse($tagWeight->hasErrors());

        $tagWeight = new TagWeight();
        $tagWeight->tag_id = $tag->id;
        $tagWeight->weight = 2;
        $tagWeight->clicks = 3;
        $tagWeight->entity = stdClass::class;
        $tagWeight->save();

        $this->tester->assertTrue($tagWeight->hasErrors());
    }

    public function testAttributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'tag_id' => 'ID tag',
            'entity' => 'The name of the class that uses the tag.',
            'weight' => 'Number of uses',
            'clicks' => 'Number of clicks by tag'
        ];

        $this->tester->assertSame($labels, (new TagWeight())->attributeLabels());
    }

    public function testGetTag()
    {
        //return $this->hasOne(Tag::class, ['id' => 'tag_id']);
        $this->tester->assertInstanceOf(ActiveQuery::class, (new TagWeight())->getTag());
    }

    public function testFind()
    {
        $this->tester->assertInstanceOf(TagWeightQuery::class, TagWeight::find());
    }
}