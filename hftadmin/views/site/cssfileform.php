<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

$siteCssfiles = array_merge([''=>Yii::t('app','Select')], GeneralHelper::getSiteCssFiles());
$csstheme = (!empty($_POST['csstheme']) ? $_POST['csstheme'] : '');
$cssdata = (!empty($_POST['cssdata']) ? $_POST['cssdata'] : '');

?>

<div>
	<h1>CSS Theme Editor</h1>

	<?= Html::beginForm(['/site/cssfileform'], 'POST'); ?>
	<p><?= Yii::t('app', 'CSS Theme'); ?><br>
	<?= Html::dropDownList('csstheme', $csstheme, $siteCssfiles, ['id'=>'csstheme']); ?></p>

  <p><?= Yii::t('app', 'Data'); ?><br>
  <?= Html::textarea('cssdata', $cssdata, ['id'=>'cssdata', 'rows'=>15, 'cols'=>60]); ?></p>

  <p><?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('cruds', 'Save'),
    [
      'id' => 'save-cssfile',
      'class' => 'btn btn-success'
    ]
	); ?></p>
  <?php Html::endForm(); ?>
</div>

<?php JSRegister::begin([
//    'key' => 'check2fa-login',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
$('#csstheme').on('change blur input', function() {
  try {
    var csstheme = $(this).val();
    console.log('csstheme=['+csstheme+']');
    if (csstheme.length>0) {
      $.post(
        '/site/getcssdata',
        {'csstheme':csstheme}, (response) => {
          var data=JSON.parse(response);
          $('#cssdata').val(data.cssdata)
        }
      );
    }
  } catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
  }
});

//$(document).ready( function() {
//});
</script>
<?php JSRegister::end(); ?>


