<?php

namespace kittools\tags\widgets;

use kittools\tags\models\Tag;
use kittools\tags\models\TagWeight;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Tag cloud for with the ability to sort tags by weight or number of clicks.
 * @package kittools\tags\widgets
 */
class CloudTagsWidget extends BaseWidget
{
    /**
     * Selection of tag tags by maximum weight
     */
    const TYPE_WEIGHT = 'weight';

    /**
     * Selection of tags by the maximum number of clicks
     */
    const TYPE_CLICKS = 'clicks';

    /**
     * Random tag selection
     */
    const TYPE_RANDOM = 'random';

    /**
     * Tag popularity by weight
     */
    const POPULARITY_TYPE_WEIGHT = 'weight';

    /**
     * Click tag popularity
     */
    const POPULARITY_TYPE_CLICKS = 'clicks';

    /**
     * The name of the class for which you want to display popular tags
     *
     * @var string
     */
    public $entity = null;

    /**
     * Sets the type of widget. Available Values:
     * - weight - popular tags by the number of uses (weight), sorted in descending order by weight
     * - clicks - popular by the number of clicks (clicks) by tag
     *
     * @var string Widget type
     */
    public $type = null;

    /**
     * Sets the value by which to determine the popularity of tags.
     *
     * @var string
     */
    public $popularityType = self::POPULARITY_TYPE_WEIGHT;

    /**
     * @var int Number of tags in the cloud
     */
    public $limit = 20;

    /**
     * @var bool Shuffle found tags
     */
    public $shuffleTags = true;

    /**
     * The coefficient of increasing the font size of the tag, depending on the popularity of the tag.
     * Tag popularity is calculated by the value of $ this-> type.
     * If 0, then the font size does not increase.
     *
     * @var float
     */
    public $coefficientIncreaseFont = 0;

    /**
     * Calculated tag popularity values
     *
     * @var array
     */
    protected $tagPopularity = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (empty($this->entity)) {
            throw new InvalidConfigException("Свойство 'entity' должно быть указано.");
        }

        if (!in_array($this->type, [static::TYPE_WEIGHT, static::TYPE_CLICKS, static::TYPE_RANDOM])) {
            throw new InvalidConfigException("Свойство 'type' должно быть указано из значений констант TYPE_*.");
        }

        if (!in_array($this->popularityType, [static::POPULARITY_TYPE_WEIGHT, static::POPULARITY_TYPE_CLICKS])) {
            throw new InvalidConfigException("Свойство 'popularityType' должно быть указано из значений констант POPULARITY_TYPE_*.");
        }

        $this->setTags();
        $this->setTagPopularity();
    }

    /**
     * Tag search
     */
    protected function setTags(): void
    {
        $query = Tag::find()
            ->innerJoin(TagWeight::tableName(), Tag::tableName() . '.id=' . TagWeight::tableName() . '.tag_id')
            ->andWhere(TagWeight::tableName() . '.entity=:entity', [':entity' => $this->entity])
            ->andWhere(['>', TagWeight::tableName() . '.weight', 0])
            ->limit($this->limit);

        switch ($this->type) {
            case static::TYPE_WEIGHT:
                $query->orderBy([TagWeight::tableName() . '.weight' => SORT_DESC]);
                break;
            case static::TYPE_CLICKS:
                $query->orderBy([TagWeight::tableName() . '.clicks' => SORT_DESC]);
                break;
            case static::TYPE_RANDOM:
                $ids = TagWeight::find()
                    ->select('tag_id')
                    ->andWhere(TagWeight::tableName() . '.entity=:entity', [':entity' => $this->entity])
                    ->andWhere(['>', TagWeight::tableName() . '.weight', 0])
                    ->column();
                shuffle($ids);
                $query->andWhere([TagWeight::tableName() . '.tag_id' => array_slice($ids, 0, $this->limit)]);
                break;
        }

        $this->tags = $query->all();

        if ($this->shuffleTags) {
            shuffle($this->tags);
        }
    }

    /**
     * @inheritdoc
     *
     * @param Tag $modelTag
     * @return string
     */
    protected function generateHtmlLink($modelTag): string
    {
        $options = [];
        if ($this->coefficientIncreaseFont && !empty($this->tagPopularity)) {
            $options['style'] = (!empty($this->tagUrlOptions['style']) ? $this->tagUrlOptions['style'] : '') . ' font-size: ' . $this->tagPopularity[$modelTag->id] . '%;';
        }
        return Html::a(
            $this->formattingTitle($modelTag->title),
            $this->tagUrl,
            ArrayHelper::merge($this->tagUrlOptions, $options)
        );
    }

    /**
     * Calculation of tag popularity and application of font size increase factor
     */
    protected function setTagPopularity(): void
    {
        if ($this->coefficientIncreaseFont && !empty($this->tags)) {
            $tags = TagWeight::find()
                ->select(['tag_id', $this->popularityType . ' AS value'])
                ->andWhere('entity=:entity', [':entity' => $this->entity])
                ->andWhere(['tag_id' => ArrayHelper::map($this->tags, 'id', 'id')])
                ->asArray()
                ->all();

            if (!empty($tags)) {
                $maxValue = max(ArrayHelper::map($tags, 'tag_id', 'value'));
                foreach ($tags as $tag) {
                    if ($maxValue > 0) {
                        $this->tagPopularity[$tag['tag_id']] = ($tag['value'] * 100 / $maxValue * $this->coefficientIncreaseFont) + 100;
                    }
                }
            }
        }
    }
}