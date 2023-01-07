<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\bootstrap4\ActiveForm;
//use yii\helpers\StringHelper;
//use richardfan\widget\JSRegister;
//use frontend\models\Usermember;
//use frontend\models\Userpay;
//use frontend\models\Userbot;
//use common\helpers\GeneralHelper;

//$userId = Yii::$app->user->id;
//$userIdentity = Yii::$app->user->identity;

//$signals = \frontend\models\Signal::getSignalsForUserBotsignal();
//$pricelistPeriods = GeneralHelper::getPricelistPeriods();
//$yesNos = GeneralHelper::getYesNos();

//$isDev = Yii::$app->user->identity->isDev();

Yii::trace('** view membership index usrid='.(\Yii::$app->user->id).' -> usermembersData: '.print_r($usermembersData, true));
Yii::trace('** view membership index umbids='.$umbids.' => userpaysData: '.print_r($userpaysData, true));
Yii::trace('** view membership index umbids='.$umbids.' => userbotsData: '.print_r($userbotsData, true));

Url::remember(); // for add/update bot form returns

?>
<div class="site-index">
  <div class="body-content">

    <div class="row">
      <div class="col text-center">
        <h2><?= Yii::t('app', 'Expired subscriptions') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col text-center">
        <p><?= Yii::t('app', 'Here are expired and inactive data shown. For current and active situation {link:seehere}see here{/link}.', ['link:seehere'=>'<a href="/membership/index">', '/link'=>'</a>']) ?></p>
      </div>
    </div>

<?php if (empty($usermembersData)) : ?>

    <div class="row">
      <div class="col text-center">
        <p><h4><?= Yii::t('app', 'You do not have historical data') ?></h4></p>

        <p><?= HTML::a(Yii::t('app', 'Buy a membership subscription'), ['/membership/subscribe'], ['class'=>'btn btn-success']) ?></p>
      </div>
    </div>

<?php else :
        include('index-details.php');
      endif; ?>
  </div>
</div>

