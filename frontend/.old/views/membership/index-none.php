<?php
use yii\helpers\Html;
?>
		<div class="row">
			<div class="col text-center">
				<p><h4><?= Yii::t('app', 'You do not have a current subscription') ?></h4></p>

				<p><?= HTML::a(Yii::t('app', 'Buy a membership subscription'), ['/membership/subscribe'], ['class'=>'btn btn-success']) ?></p>
			</div>
		</div>
