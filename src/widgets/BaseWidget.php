<?php

namespace kittools\tags\widgets;

use kittools\tags\models\Tag;
use yii\base\Widget;
use yii\helpers\Html;

class BaseWidget extends Widget
{
    /**
     * @var Tag[] tag list
     */
    public $tags;

    /**
     * @var string Link for clicking on a tag
     */
    public $tagUrl = null;

    /**
     * @var array Parameters for link to tag
     * Standard parameters for the <a> tag
     */
    public $tagUrlOptions = [];

    /**
     * @var string Template for outputting tags
     * You can specify your own template, for example @app/views/widgets/my-tags-entity-template
     */
    public $templatePath = 'tags-entity-default';

    /**
     * @var string Tag register. By default, the case is tag as-is.
     * Available register options:
     * 1. null - "as is"
     * 2. lower - upper case
     * 3. upper - lower case
     */
    public $caseRegister = null;

    /**
     * @var string Tag Separation Symbol(s)
     */
    public $delimiter = ' ';

    /**
     * @var string Symbol(s) before the tag
     */
    public $tagPrefix = '#';

    /**
     * @var string Character(s) after tag
     */
    public $tagPostfix = null;

    /**
     * @var array Array of tags created from $this->tags using widget settings
     */
    protected $data = [];

    /**
     * @inheritdoc
     * @return string
     */
    public function run(): string
    {
        $this->prepareData();

        return $this->render($this->templatePath, [
            'tags' => $this->data
        ]);
    }

    /**
     * Preparing tags in accordance with the set widget parameters
     */
    protected function prepareData(): void
    {
        if (!empty($this->tags)) {
            $countTags = count($this->tags);
            foreach ($this->tags as $modelTag) {
                if ($this->tagUrl) {
                    $this->tagUrl['tag'] = $modelTag->title;
                    $tag = $this->generateHtmlLink($modelTag);
                } else {
                    $tag = $this->formattingTitle($modelTag->title);
                }

                if ((count($this->data) + 1) < $countTags) {
                    $tag .= $this->delimiter;
                }

                $this->data[] = $tag;
            }
        }
    }

    /**
     * Tag Name Formatting
     *
     * @param string $title
     * @return string
     */
    protected function formattingTitle($title): string
    {
        return (string)$this->tagPrefix . $this->setCaseRegister($title) . (string)$this->tagPostfix;
    }

    /**
     * Generates an html link to the tag.
     *      * The method is needed to override the settings for generating a tag in inherited classes.
     *
     * @param Tag $modelTag
     * @return string
     */
    protected function generateHtmlLink($modelTag): string
    {
        return Html::a($this->formattingTitle($modelTag->title), $this->tagUrl, $this->tagUrlOptions);
    }

    /**
     * Tag case setting
     *
     * @param string $title Tag name
     * @return string
     */
    protected function setCaseRegister($title): string
    {
        switch ($this->caseRegister) {
            case 'lower';
                $title = mb_strtolower($title);
                break;
            case 'upper';
                $title = mb_strtoupper($title);
                break;
        }

        return $title;
    }
}