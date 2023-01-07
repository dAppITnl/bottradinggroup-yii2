<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<div class="site-index">
	<div class="body-content">

		<div class="row">
			<div class="container d-flex justify-content-center">
				<div class="text-left">
					<h4><?= Yii::t('app', 'Join a tight-knit community!<br>Follow experienced (bot) traders.<br>Keep up to date with the latest bots<br>indicators and much more!') ?></h4>
					<p><br>
						<?= Html::a(Yii::t('app', 'Sign up'), ['/site/signup'], ['class'=>'btn btn-success']) ?>
						<?= Yii::t('app', ' or ') ?>
						<?= Html::a(Yii::t('app', 'Login'), ['/site/login'], ['class'=>'btn btn-success']) ?>
					</p>
				</div>
			</div>
		</div>

	</div>
</div>
