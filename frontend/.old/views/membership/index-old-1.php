<?php

use Yii;
use yii\helpers\Html;

?>
<div class="site-index">
  <div class="body-content">

		<div class="row">
			<div class="col text-center">
				<h2><?= Yii::t('app', 'Active subscriptions') ?></h2>
			</div>
		</div>

		<?php	include (empty($usermemberData) ? 'index-none.php' :  'index-currents.php') ?>

	</div>
</div>
