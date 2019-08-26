<?php


namespace kittools\tags\tests\unit\components\behaviors;


use Codeception\Test\Unit;
use kittools\tags\models\Tag;
use kittools\tags\models\TagWeight;
use kittools\tags\tests\_app\models\Product1;
use kittools\tags\tests\_app\models\Product2;

class TagWeightBehaviorTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var Tag */
    private $tag1;

    /** @var Tag */
    private $tag2;

    protected function setUp()
    {
        parent::setUp();

        $this->tag1 = new Tag();
        $this->tag1->title = 'tag1';
        $this->tag1->save();

        $this->tag2 = new Tag();
        $this->tag2->title = 'tag2';
        $this->tag2->save();
    }

    private function getWeight(Tag $tag): ?TagWeight
    {
        return TagWeight::find()->byEntityAndTagId(Product2::class, $tag->id)->one();
    }

    public function testInsert()
    {
        $product = new Product2();
        $product->title = 'product2';
        $product->tagList = ['tag1'];
        $product->save();

        $product = new Product2();
        $product->title = 'product3';
        $product->tagList = ['tag1'];
        $product->save();

        $this->tester->assertSame(2, $this->getWeight($this->tag1)->weight);

        $product = new Product1();
        $product->title = 'product1';
        $product->tagList = ['tag1'];
        $product->save();

        $this->tester->assertSame(2, $this->getWeight($this->tag1)->weight);
    }

    public function testUpdate()
    {
        $product = new Product2();
        $product->title = 'product2';
        $product->tagList = ['tag1'];
        $product->save();

        $product = new Product2();
        $product->title = 'product3';
        $product->tagList = ['tag2'];
        $product->save();

        $this->tester->assertSame(1, $this->getWeight($this->tag1)->weight);

        $product->tagList = ['tag1', 'tag2'];
        $product->save();

        $product = new Product1();
        $product->title = 'product1';
        $product->tagList = ['tag1'];
        $product->save();

        $this->tester->assertSame(2, $this->getWeight($this->tag1)->weight);
        $this->tester->assertSame(1, $this->getWeight($this->tag2)->weight);
    }

    public function testDelete()
    {
        $product1 = new Product2();
        $product1->title = 'product2';
        $product1->tagList = ['tag1'];
        $product1->save();

        $product2 = new Product2();
        $product2->title = 'product3';
        $product2->tagList = ['tag1'];
        $product2->save();

        $this->tester->assertSame(2, $this->getWeight($this->tag1)->weight);

        $product1->tagList = [];
        $product1->save();
        $product2->tagList = [];
        $product2->save();

        $product = new Product1();
        $product->title = 'product1';
        $product->tagList = ['tag1'];
        $product->save();

        $this->tester->assertNull($this->getWeight($this->tag1));
    }
}