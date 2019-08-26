<?php


namespace kittools\tags\tests\unit\models\query;


use Codeception\Test\Unit;
use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
use kittools\tags\tests\_app\models\Product1;
use kittools\tags\tests\_app\models\Product2;

class TagEntityRelationQueryTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Tag
     */
    private $tag1;

    /**
     * @var Tag
     */
    private $tag2;

    /**
     * @var Tag
     */
    private $tag3;

    /**
     * @var Product1
     */
    private $product1;

    /**
     * @var Product1
     */
    private $product11;

    /**
     * @var Product2
     */
    private $product2;

    protected function setUp()
    {
        parent::setUp();

        $this->tag1 = new Tag();
        $this->tag1->title = 'Tag1';
        $this->tag1->save();

        $this->tag2 = new Tag();
        $this->tag2->title = 'Tag2';
        $this->tag2->save();

        $this->tag3 = new Tag();
        $this->tag3->title = 'Tag3';
        $this->tag3->save();

        $this->product1 = new Product1();
        $this->product1->title = 'Product1';
        $this->product1->tagList = ['Tag1', 'Tag2'];
        $this->product1->save();

        $this->product11 = new Product1();
        $this->product11->title = 'Product11';
        $this->product11->tagList = ['Tag1', 'Tag3'];
        $this->product11->save();

        $this->product2 = new Product2();
        $this->product2->title = 'Product2';
        $this->product2->tagList = ['Tag2', 'Tag3'];
        $this->product2->save();
    }

    public function testByTagId()
    {
        $relations = TagEntityRelation::find()
            ->byTagId($this->tag2->id)
            ->orderBy(['entity' => SORT_ASC])
            ->all();

        $this->tester->assertSame(get_class($this->product1), $relations[0]->entity);
        $this->tester->assertSame(get_class($this->product2), $relations[1]->entity);
    }

    public function testByEntity()
    {
        $relations = TagEntityRelation::find()
            ->byEntity(get_class($this->product1))
            ->orderBy(['tag_id' => SORT_ASC])
            ->all();

        $this->tester->assertSame($this->tag1->id, $relations[0]->tag_id);
        $this->tester->assertSame($this->tag1->id, $relations[1]->tag_id);
        $this->tester->assertSame($this->tag2->id, $relations[2]->tag_id);
        $this->tester->assertSame($this->tag3->id, $relations[3]->tag_id);
    }

    public function testByEntityId()
    {
        $relations = TagEntityRelation::find()
            ->byEntityId($this->product1->id)
            ->orderBy(['tag_id' => SORT_ASC])
            ->all();

        $this->tester->assertSame($this->tag1->id, $relations[0]->tag_id);
        $this->tester->assertSame($this->tag2->id, $relations[1]->tag_id);
    }

    public function testWeightTagForEntity()
    {
        $relations = TagEntityRelation::find()
            ->weightTagForEntity(get_class($this->product1), $this->tag2->id)
            ->all();

        $this->tester->assertCount(1, $relations);
    }
}