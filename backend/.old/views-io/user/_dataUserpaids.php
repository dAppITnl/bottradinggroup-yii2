<?php
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $model->userpaids,
        'key' => 'id'
    ]);
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'usrpay_startdt',
        'usrpay_enddt',
        'usrpay_payamount',
        'usrpay_paysymbol',
        'usrpay_rate',
        'usrpay_ratesymbol',
        'usrpay_paiddt',
        'usrpay_createdt',
        'usrpay_updatedt',
        'usrpay_deletedt',
        'usrpay_remarks:ntext',
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'userpaids'
        ],
    ];
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'pjax' => true,
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export']
            ]
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'persistResize' => false,
    ]);
