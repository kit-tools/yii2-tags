<?php


namespace kittools\tags\tests\unit\models\search;

use Codeception\Test\Unit;
use kittools\tags\models\search\TagSearch;
use kittools\tags\models\Tag;
use kittools\tags\tests\_app\models\Product1;
use yii\data\ActiveDataProvider;

class TagSearchTest extends Unit
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
        $this->product->tagList = ['tag1', 'tag2', 'tag3'];
        $this->product->save();
    }

    public function testSearch()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search([]);

        $this->tester->assertInstanceOf(ActiveDataProvider::class, $dataProvider);

        $this->tester->assertSame(3, $dataProvider->getTotalCount());

        $dataProvider = $searchModel->search(['TagSearch' => ['title' => 'tag']]);
        $this->tester->assertSame(3, $dataProvider->getTotalCount());

        $dataProvider = $searchModel->search(['TagSearch' => ['title' => 'tag1']]);
        $this->tester->assertSame(1, $dataProvider->getTotalCount());

        /** @var Tag $tag */
        $tag = $this->product->getTags()->all()[0];

        $this->tester->assertSame($tag->title, $dataProvider->getModels()[0]->title);

        $dataProvider = $searchModel->search(['TagSearch' => ['id' => $tag->id]]);
        $this->tester->assertSame($tag->id, $dataProvider->getModels()[0]->id);

        $dataProvider = $searchModel->search(['TagSearch' => ['id' => 'id']]);
        $this->tester->assertSame(3, $dataProvider->getTotalCount());
    }
}