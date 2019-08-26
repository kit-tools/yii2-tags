<?php

namespace kittools\tags\tests\unit\models;


use Codeception\Test\Unit;
use kittools\tags\models\query\TagQuery;
use kittools\tags\models\Tag;
use yii\db\ActiveQuery;

class TagTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testGetTagEntityRelations()
    {
        $this->tester->assertInstanceOf(ActiveQuery::class, (new Tag())->getTagEntityRelations());
    }

    public function testFind()
    {
        $this->tester->assertInstanceOf(TagQuery::class, Tag::find());
    }

    public function testGetTagWeights()
    {
        $this->tester->assertInstanceOf(ActiveQuery::class, (new Tag())->getTagWeights());
    }

    public function testAttributeLabels()
    {
        $this->tester->assertSame(['id' => 'ID', 'title' => 'Tag name'], (new Tag())->attributeLabels());
    }

    public function testTableName()
    {
        $this->tester->assertSame('{{%tag}}', Tag::tableName());
    }

    public function testRules()
    {
        $tag = new Tag();
        $tag->title = '';

        $tag->validate('title');
        $this->tester->assertSame('Tag name cannot be blank.', $tag->getFirstError('title'));

        $tag->title = str_repeat('a', 31);
        $tag->validate('title');
        $this->tester->assertSame('Tag name should contain at most 30 characters.', $tag->getFirstError('title'));

        $tag->title = ' trim ';
        $tag->validate('title');
        $this->tester->assertSame('trim', $tag->title);
    }

    public function testRulesUniqueValue()
    {
        $tag = new Tag();
        $tag->title = 'unique title';
        $tag->save();

        $tag = new Tag();
        $tag->title = 'unique title';
        $tag->save();
        $this->tester->assertSame('Tag name "unique title" has already been taken.', $tag->getFirstError('title'));
    }
}
