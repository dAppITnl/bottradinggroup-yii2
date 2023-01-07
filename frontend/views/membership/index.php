<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

Url::remember(); // for add/update bot form returns

?>
<div class="site-index">
  <div class="body-content">

    <div class="row">
      <div class="col text-center">
        <h2><?= Yii::t('app', 'Active subscriptions') ?></h2>
      </div>
    </div>

		<div class="row">
			<div class="col text-center">
        <p><?= Yii::t('app', '{link:seehere}Click here{/link} for expired subscriptions', ['link:seehere'=>'<a href="/membership/history">', '/link'=>'</a>']) ?></p>
      </div>
    </div>

<?php if (empty($usermembersData)) : ?>

    <div class="row">
      <div class="col text-center">
        <p><h4><?= Yii::t('app', 'You do not have a current subscription') ?></h4></p>

        <p><?= HTML::a(Yii::t('app', 'Buy a membership subscription'), ['/membership/subscribe'], ['class'=>'btn btn-success']) ?></p>
      </div>
    </div>

<?php else :
				include('index-details.php');
		  endif; ?>
  </div>
</div>
