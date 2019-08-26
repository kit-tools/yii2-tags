<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model kittools\tags\models\Tag */

$this->title = 'Добавление тега';
$this->params['breadcrumbs'][] = ['label' => 'All tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
