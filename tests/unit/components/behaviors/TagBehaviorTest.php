<?php


namespace kittools\tags\tests\unit\components\behaviors;

use Codeception\Test\Unit;
use kittools\tags\models\Tag;
use kittools\tags\models\TagWeight;
use kittools\tags\tests\_app\models\Product1;
use kittools\tags\tests\_app\models\Product2;

class TagBehaviorTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var Product1 */
    private $product;

    protected function setUp()
    {
        parent::setUp();

        $this->product = new Product1();
        $this->product->title = 'product1';
        $this->product->tagList = ['tag1', 'tag2'];
        $this->product->save();
    }

    public function testAddTag()
    {
        $tags = $this->product->getTags()->orderBy([Tag::tableName() . '.title' => SORT_DESC])->all();
        $this->tester->assertCount(2, $tags);
        $this->tester->assertSame('tag2', $tags[0]->title);
        $this->tester->assertSame('tag1', $tags[1]->title);

        /** @var Tag $tag2 */
        $tag2 = $tags[0];
        $weight = $tag2->getTagWeights()
            ->andWhere(TagWeight::tableName() . '.entity=:entity', [':entity' => get_class($this->product)])
            ->one();

        $this->tester->assertSame(get_class($this->product), $weight->entity);
        $this->tester->assertSame(1, $weight->weight);

        $product = new Product1();
        $product->title = 'product2';
        $product->tagList = ['tag2'];
        $product->save();

        $weight = $tag2->getTagWeights()
            ->andWhere(TagWeight::tableName() . '.entity=:entity', [':entity' => get_class($this->product)])
            ->one();
        $this->tester->assertSame(2, $weight->weight);

        $product = new Product2();
        $product->title = 'product-2';
        $product->save();

        $this->tester->assertFalse($product->hasErrors());
    }

    public function testRemoveTag()
    {
        $this->tester->assertCount(2, $this->product->getTags()->all());

        $this->product->tagList = ['tag1'];
        $this->product->save();
        $this->tester->assertCount(1, $this->product->getTags()->all());

        $this->product->tagList = [];
        $this->product->save();
        $this->tester->assertSame('Tag List cannot be blank.', $this->product->getFirstError('tagList'));

        $this->tester->assertCount(1, $this->product->getTags()->all());

        $product = new Product2();
        $product->title = 'product-2';
        $this->tester->assertTrue($product->save());

        $product->tagList = ['tag'];
        $this->tester->assertTrue($product->save());

        $product->tagList = [];
        $this->tester->assertTrue($product->save());
    }
}