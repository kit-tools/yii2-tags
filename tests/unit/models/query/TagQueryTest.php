<?php


namespace kittools\tags\tests\unit\models\query;


use Codeception\Test\Unit;
use kittools\tags\models\Tag;

class TagQueryTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testByTitle()
    {
        $tag = new Tag();
        $tag->title = 'testTitle';
        $tag->save();

        $results = Tag::find()->byTitle('testTitle')->all();
        $this->tester->assertCount(1, $results);

        $results = Tag::find()->byTitle('itl')->all();
        $this->tester->assertCount(0, $results);
    }
}