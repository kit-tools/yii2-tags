<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \kittools\tags\models\search\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tags';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add tag', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>* The number of uses of the tag is displayed under the tag name, in parentheses the number of clicks on the tag
        in the model</p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'text-align: center; width: 50px;'
                ]
            ],

            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = [];
                    if (!empty($model->tagWeights)) {
                        /* @var $modelWeight \kittools\tags\models\TagWeight */
                        foreach ($model->tagWeights as $modelWeight) {
                            $html[] = $modelWeight->entity
                                . ': <strong>' . $modelWeight->weight . ' (' . $modelWeight->clicks . ')</strong>';
                        }
                    }
                    return Html::a($model->title, ['update', 'id' => $model->id])
                        . Html::tag(
                            'div',
                            empty($html) ? 'not used' : 'used: ' . implode(', ', $html),
                            ['class' => 'tag-weight ' . (empty($html) ? 'text-danger' : 'text-success')]
                        );
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                'headerOptions' => [
                    'style' => 'text-align: center; width: 50px;'
                ],
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        $options = array_merge([
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-method' => 'post',
                        ]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            ['delete', 'id' => $model->id],
                            $options
                        );
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<style>
    .tag-weight {
        font-size: 12px;
    }

    .tag-weight strong {
        color: #333333;
    }
</style>