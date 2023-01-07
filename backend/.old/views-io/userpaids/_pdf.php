<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Userpaids */

$this->title = $model->usrpay_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userpaids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userpaids-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Userpaids').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'usrpay_name',
        [
                'attribute' => 'usrpayUser.id',
                'label' => Yii::t('app', 'Usrpay UserId')
            ],
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
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
