# Yii2-tags behavior

The extension allows you to add tags to different models. Counts the weight of tags and the number of clicks per tag 
for each model. There is also an admin panel for adding tags. And a few widgets to display tags.

## Installation

The preferred way to install this extension is through composer.

Either run:

```composer require kittools/yii2-tags```

or add

```"kittools/yii2-tags": "^1.0.0"```

to the require section of your composer.json file.

## Usage

### Admin

Add to config (config/main.php или config/web.php)

```php
'controllerMap' => [
    'tag' => 'kittools\tags\controllers\TagController'
],
```

### Property $tagList

In the model with which you want to associate tags, add:

```php
<?php
...
/**
 * @var array list of tags entered by the user.
 */
public $tagList = [];
...
```

### Validation rules
```php
<?php
public function rules(){
    ...
    ['tagList', 'required', 'skipOnEmpty' => false],
    ['tagList', 'each', 'rule' => ['string'], 'message' => 'Tags are filled incorrectly.'],
    ...
}
```

If the tags are optional, then you need to delete the line

```php
    ['tagList', 'required'],
```

### Behavior

```php
public function behaviors()
{
    return [
        ...
        'tagBehavior' => [
            'class' => TagBehavior::className(),
        ],
        ...
    ];
}
```

TagBehavior works with events:
- ActiveRecord :: EVENT_AFTER_INSERT, ActiveRecord :: EVENT_AFTER_UPDATE - adds new tags, establishes / removes model 
and tag relationships.
- ActiveRecord :: EVENT_AFTER_DELETE - when deleting a model, removes links with tags. Linked tags are not removed.

Important!!! If there are connected tags in the model, and the tags will not be transferred when saving, then all links 
with the tags will be deleted!
To avoid this situation, before saving the model, you need to untie the TagBehavior

```php
$model->detachBehavior('tagBehavior');
``` 
    

### One-to-many relationship

```php
<?php
use kittools\tags\models\Tag;
use kittools\tags\models\TagEntityRelation;
...
public function getTags()
{
    return $this->hasMany(Tag::class, ['id' => 'tag_id'])
        ->viaTable(TagEntityRelation::tableName(), ['entity_id' => 'id'], function($query){
            $query->andWhere([TagEntityRelation::tableName() . '.entity' => static::class]);
        });
}
```

The method adds a model link to odic-to-many tags through an intermediate table.


### Form adding tags in creating / editing models

Add tag widget

```php
<?php
use kittools\tags\widgets\TagInputWidget;
...
?>

...
<?= $form->field($model, 'tagList')
    ->widget(TagInputWidget::className(), [
        'pluginOptions' => [
            // maximum number of tags that can be selected
            'maximumSelectionLength'=> 5,
        ]
    ])
    ->hint('Press "Space", "," or Enter to separate tags from each other.')
    ->label('Tags'); ?>
...

```

The widget loads the tags with an ajax request. Minimum word length after which tags will be loaded, 2 characters.

or

```php
<?php
use kittools\tags\widgets\TagInputWidget;
use yii\helpers\ArrayHelper;
use kittools\tags\models\Tag;
...
?>
...
<?= $form->field($model, 'tagList')
    ->widget(TagInputWidget::className(), [
        'pluginOptions' => [
            // maximum number of tags that can be selected
            'maximumSelectionLength'=> 2,
            // Minimum tag length for listing tags, default 2
            'minimumInputLength' => 0,
            // disabling tag loading by ajax request
            'ajax' => null,
        ],
        // Tags list
        'data' => ArrayHelper::map(Tag::find()->all(), 'title', 'title')
    ])
    ->hint('Press "," or "Enter" to separate tags from each other')
    ->label('Tags'); ?>
...

```

The widget does not load tags through an ajax request. A list of tags is loaded when the page loads.

Tag widget inherited from [kartik\select2\Select2](http://demos.krajee.com/widget-details/select2).

More information about widget settings can be found at [https://select2.org/](https://select2.org/)

### Tag output widget

When using the widget in the list of materials, do not forget about the "problem of N + 1 queries".

```php
<?php
use kittools\tags\widgets\TagsEntityWidget;
...
echo TagsEntityWidget::widget([
    'model' => $model,
    'caseRegister' => 'upper',
    'tagUrl' => ['site/tag'],
    'delimiter' => ', ',
 ]);
...

```
Detailed description of widget settings in a file ```kittools\tags\widgets\TagsEntityWidget```

### Tag cloud widget

```php
<?php
use kittools\tags\widgets\CloudTagsWidget;
use app\models\News;
...
echo CloudTagsWidget::widget([
    'entity' => News::className(),
    'type' => CloudTagsWidget::TYPE_WEIGHT,
    'shuffleTags' => false,
    'tagUrl' => ['/news/tag'],
    'coefficientIncreaseFont' => 1,
    'tagPrefix' => '',
    'tagUrlOptions' => ['style' => 'color: #003366;', 'target' => '_blank']
]);
...
```

more information about widget settings can be found in ```kittools\tags\widgets\CloudTagsWidget```