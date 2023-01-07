<?php

//use Yii;
use yii\helpers\Html;
use yii\helpers\Url; //manager;
use yii\helpers\ArrayHelper;
use \kartik\grid\GridView;
use \backend\models\User;


/*$colorPluginOptions =  [
    'showPalette' => true,
    'showPaletteOnly' => true,
    'showSelectionPalette' => true,
    'showAlpha' => false,
    'allowEmpty' => false,
    'preferredFormat' => 'name',
    'palette' => [
        [
            "white", "black", "grey", "silver", "gold", "brown",
        ],
        [
            "red", "orange", "yellow", "indigo", "maroon", "pink"
        ],
        [
            "blue", "green", "violet", "cyan", "magenta", "purple",
        ],
    ]
];*/

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    'width'=>'36px',
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 6],
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'class' => 'kartik\grid\RadioColumn',
    'width' => '36px',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
],
[
    'class' => 'kartik\grid\ExpandRowColumn',
    'width' => '50px',
    'value' => function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
    },
    // show row expanded for even numbered keys
    'detailUrl' => Url::to(['/site/book-details']),
    'value' => function ($model, $key, $index) {
        if ($key % 2 === 0) {
            return GridView::ROW_EXPANDED;
        }
        return GridView::ROW_COLLAPSED;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'expandOneOnly' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'name',
    'pageSummary' => 'Total',
    'vAlign' => 'middle',
    'width' => '210px',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->bot_lock); // do not allow editing of inactive records
    },
    /*'editableOptions' =>  function ($model, $key, $index) use ($colorPluginOptions) {
        return [
            'header' => 'Name',
            'size' => 'md',
            'afterInput' => function ($form, $widget) use ($model, $index, $colorPluginOptions) {
                return $form->field($model, "color")->widget(\kartik\color\ColorInput::classname(), [
                    'showDefaultPalette' => false,
                    'options' => ['id' => "color-{$index}"],
                    'pluginOptions' => $colorPluginOptions,
                ]);
            }
        ];
    }*/
],
/*[
    'attribute' => 'color',
    'value' => function ($model, $key, $index, $widget) {
        return "<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>';
    },
    'width' => '8%',
    'filterType' => GridView::FILTER_COLOR,
    'filterWidgetOptions' => [
        'showDefaultPalette' => false,
        'pluginOptions' => $colorPluginOptions,
    ],
    'vAlign' => 'middle',
    'format' => 'raw',
    'noWrap' => true, //$this->noWrapColor
],*/
[
    'attribute' => 'botusr_id',
    'vAlign' => 'middle',
    'width' => '180px',
    'value' => function ($model, $key, $index, $widget) {
        return Html::a($model->botusrCreated->username,
            '#',
            ['title' => 'View user detail', 'onclick' => 'alert("This will open the user page. Disabled")']);
    },
    'filterType' => GridView::FILTER_SELECT2,
    'filter' => ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
    'filterWidgetOptions' => [
        'pluginOptions' => ['allowClear' => true],
    ],
    'filterInputOptions' => ['placeholder' => 'Any user', 'multiple' => true], // allows multiple authors to be chosen
    'format' => 'raw'
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'bot_createdt',
    'hAlign' => 'center',
    'vAlign' => 'middle',
    'width' => '9%',
    'format' => 'date',
    'xlFormat' => "mmm\\-dd\\, \\-yyyy",
    'headerOptions' => ['class' => 'kv-sticky-column'],
    'contentOptions' => ['class' => 'kv-sticky-column'],
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->bot_lock); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Created Date',
        'size' => 'md',
        'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
        'widgetClass' =>  'kartik\datecontrol\DateControl',
        'options' => [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
            'displayFormat' => 'dd.MM.yyyy',
            'saveFormat' => 'php:Y-m-d',
            'options' => [
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]
    ],
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'bot_costmonth',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->bot_lock); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Buy Amount', 
        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '7%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'attribute' => 'bot_costmonth', //'sell_amount', 
    'vAlign' => 'middle',
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Buy + Sell<br>(BS)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        return 2; //$widget->col(5, $p) + $widget->col(6, $p);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Buy %<br>(100 * B/BS)', 
    'vAlign' => 'middle',
    'hAlign' => 'right', 
    'width' => '7%',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        return 0; //$widget->col(9, $p) != 0 ? $widget->col(7, $p) * 100 / $widget->col(9, $p) : 0;
    },
    'format' => ['decimal', 2],
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'mergeHeader' => true,
    'pageSummary' => true,
    'pageSummaryFunc' => GridView::F_AVG,
    'footer' => true
],
[
    'class' => 'kartik\grid\BooleanColumn',
    'attribute' => 'bot_lock',
    'vAlign' => 'middle'
],
[
    'class' => 'kartik\grid\ActionColumn',
    //'dropdown' => $this->dropdown,
    'dropdownOptions' => ['class' => 'float-right'],
    'urlCreator' => function($action, $model, $key, $index) { return '#'; },
    'viewOptions' => ['title' => 'This will launch the book details page. Disabled for this demo!', 'data-toggle' => 'tooltip'],
    'updateOptions' => ['title' => 'This will launch the book update page. Disabled for this demo!', 'data-toggle' => 'tooltip'],
    'deleteOptions' => ['title' => 'This will launch the book delete action. Disabled for this demo!', 'data-toggle' => 'tooltip'],
    'headerOptions' => ['class' => 'kartik-sheet-style'],
],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'pageSummary' => '<small>(amounts in $)</small>',
    'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl']
],
];


?>
<h1>Signals</h1>

<?= GridView::widget([
        'id' => 'kv-grid-signals',
        'dataProvider' => $botDataProvider,
        'filterModel' => $botSearchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar' =>  [
            [
                'content' =>
                    Html::button('<i class="fas fa-plus"></i>', [
                        'class' => 'btn btn-success',
                        'title' => Yii::t('app', 'Add Bot'),
                        'onclick' => 'alert("This will launch the bot add form.");'
                    ]) . ' '.
                    Html::a('<i class="fas fa-redo"></i>', ['grid-demo'], [
                        'class' => 'btn btn-outline-secondary',
                        'title'=>Yii::t('app', 'Reset Grid'),
                        'data-pjax' => 0,
                    ]),
                'options' => ['class' => 'btn-group mr-2 me-2']
            ],
            '{export}',
            '{toggleData}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        // parameters from the demo form
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fas fa-book"></i>  Bot shop',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => ['html','csv','txt','xls','pdf','json'], // or GridView::HTML, GridView::CSV, ..
        //'itemLabelSingle' => 'bot',
        //'itemLabelPlural' => 'bots'
    ]);


