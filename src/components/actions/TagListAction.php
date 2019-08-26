<?php

namespace kittools\tags\components\actions;

use kittools\tags\models\Tag;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\web\Response;

class TagListAction extends Action
{
    /**
     * @var int minimum search query length
     */
    public $minQueryLength = 1;

    /**
     * @var int maximum search query length
     */
    public $maxQueryLength = 30;

    /**
     * @return bool
     */
    public function beforeRun()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeRun();
    }

    /**
     * @param $query
     * @return array
     */
    public function run($query): array
    {
        $model = new DynamicModel(compact('query'));
        $model->addRule(['query'], 'filter', ['filter' => 'trim']);
        $model->addRule(['query'], 'string', ['min' => $this->minQueryLength, 'max' => $this->maxQueryLength]);
        $model->validate();

        if ($model->hasErrors()) {
            return ['results' => []];
        }

        return [
            'results' => Tag::find()
                ->select(['title AS id', 'title AS text'])
                ->andWhere(['LIKE', 'title', $model->query])
                ->asArray()
                ->all()
        ];
    }
}