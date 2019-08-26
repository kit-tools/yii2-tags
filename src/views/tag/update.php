<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model kittools\tags\models\Tag */

$this->title = 'Tag editing ';
$this->params['breadcrumbs'][] = ['label' => 'All tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title . ': ' . $model->title;
?>
<div class="tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
