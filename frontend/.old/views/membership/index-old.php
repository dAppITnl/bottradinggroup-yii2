<?php

use Yii;
use yii\helpers\Html;

?>
<div class="site-index">
  <div class="body-content">

		<div class="row">
			<div class="col text-center">
				<h2><b><?= Yii::t('app', 'Your membership overview') ?>:</b></h2>
			</div>
		</div>

		<?php	include (empty($usermembersModel) ? 'index-none.php' :  'index-currents.php') ?>

	</div>
</div>
