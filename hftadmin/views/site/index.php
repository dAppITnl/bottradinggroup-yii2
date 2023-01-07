<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\GeneralHelper;

/* @var $this yii\web\View */

$this->title = 'Botsignals HFTAdmin site';

?>
<div class="site-index">
	<div class="body-content">
		<div class="jumbotron text-center bg-transparent">
			<h1 class="display-4"><?= $this->title ?></h1>
			<p class="lead">Botsignalen (en meer) hftadmin tbv volledige adminstratie en beheer.</p>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-12 col-md-12">
					<h2><?= Yii::t('app', 'Current users') ?>:</h2>
					<?= Yii::t('app', 'Total') ?>: <?= count($userData) ?>
					<p><?php if (!empty($userData)) : ?>
						<table class="table">
							<tr>
								<th><?= Yii::t('app', '1') ?></th>
								<th><?= Yii::t('app', '2') ?></th>
								<th><?= Yii::t('app', '3') ?></th>
							</tr>
							<?php foreach($userData as $usrid => $usrData) : ?>
							<tr>
								<td><?= $usrData['1'] ?></td>
								<td><?= $usrData['2'] ?></td>
								<td><?= $mbrData['3'] ?></td>
							</tr>
							<?php endforeach; ?>
						</table>
						<?php else : ?>
							<?= Yii::t('app', 'No user data available.') ?>
						<?php endif; ?>
					</p>
				</div>
			</div>

    	<!-- div class="row">
      	<div class="col">
        	<h2></h2>
	        <p></p>
  	    </div>
    	</div -->
		</div>
  </div>
</div>
