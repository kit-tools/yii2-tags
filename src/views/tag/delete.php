<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var \kittools\tags\models\Tag $model */

$this->title = 'Tag removal';
$this->params['breadcrumbs'][] = ['label' => 'All tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3>Remove tag "<u><?= $model->title ?></u>" and all links with it?</h3><?php

if (empty($model->tagWeights)) {
    echo 'No associations found';
} else {
    /* @var $modelWeight \kittools\tags\models\TagWeight */
    foreach ($model->tagWeights as $modelWeight) { ?>
        <p><?= $modelWeight->entity . ': weight <strong>' . $modelWeight->weight . '</strong>, clicks <strong>' . $modelWeight->clicks; ?></strong></p><?php
    }
} ?>
    <hr><?php
$form = ActiveForm::begin(); ?>

    <div class="form-group">
<?= Html::submitButton('Remove tag and all links', [
    'name' => 'confirmed',
    'value' => 'UYrC6VTou',
    'class' => 'btn btn-success'
]); ?>

<?= Html::a('Cancel delete', ['index'], ['class' => 'btn btn-default']); ?>
    </div><?php

ActiveForm::end();
