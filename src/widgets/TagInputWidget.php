<?php

namespace kittools\tags\widgets;

use kartik\select2\Select2;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class TagInputWidget extends Select2
{
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    public function init(): void
    {
        parent::init();
        if (!$this->hasModel()) {
            throw new InvalidConfigException("Either 'model' properties must be specified.");
        }

        $this->options = ArrayHelper::merge([
            'placeholder' => 'Введите теги',
            'multiple' => true
        ], $this->options);

        $this->pluginOptions = ArrayHelper::merge([
            'tags' => true,
            'tokenSeparators' => [','],
            'minimumInputLength' => 2,
            'ajax' => [
                'url' => '/tag/tag-list',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {query:params.term}; }'),
                'cache' => true
            ],
        ], $this->pluginOptions);
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        $this->model->tagList = $this->setTagList();
        $this->value = $this->model->tagList;

        parent::run();
    }

    /**
     * @return array
     */
    protected function setTagList(): array
    {
        return ArrayHelper::merge(
            ArrayHelper::map($this->model->tags, 'title', 'title'),
            empty($this->model->tagList) ? [] : $this->model->tagList
        );
    }
}