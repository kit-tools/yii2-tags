<?php


namespace kittools\tags\tests\unit\models\query;


use Codeception\Test\Unit;
use kittools\tags\models\Tag;
use kittools\tags\models\TagWeight;
use kittools\tags\tests\_app\models\Product1;

class TagWeightQueryTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testByTitle()
    {
        $product = new Product1();
        $product->title = 'test product1';
        $product->tagList = ['tag1', 'tag2'];
        $product->save();

        $product = new Product1();
        $product->title = 'test product11';
        $product->tagList = ['tag1'];
        $product->save();

        $tag = Tag::find()->byTitle('tag1')->one();
        $tagWeight = TagWeight::find()->byEntityAndTagId(Product1::class, $tag->id)->one();
        $this->tester->assertSame(2, $tagWeight->weight);

        $tag = Tag::find()->byTitle('tag2')->one();
        $tagWeight = TagWeight::find()->byEntityAndTagId(Product1::class, $tag->id)->one();
        $this->tester->assertSame(1, $tagWeight->weight);
    }
}