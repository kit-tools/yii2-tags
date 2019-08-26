<?php

namespace kittools\tags\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;

class TagsEntityWidget extends BaseWidget
{
    /**
     * @var Model The model the tags are associated with.
     */
    public $model;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (!$this->model instanceof Model) {
            throw new InvalidConfigException("Either 'model' properties must be specified.");
        }

        $this->tags = $this->model->tags;
    }
}