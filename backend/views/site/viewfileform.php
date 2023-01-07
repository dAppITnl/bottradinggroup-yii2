<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

$viewfiles = array_merge([''=>Yii::t('app','Select')], GeneralHelper::getFrontendViewFiles());
$viewfile = (!empty($_POST['viewfile']) ? $_POST['viewfile'] : '');
$filedata = (!empty($_POST['filedata']) ? $_POST['filedata'] : '');

?>

<div>

	<h1>Frontend Viewfile Editor</h1>

	<?= Html::beginForm(['/site/viewfileform'], 'POST'); ?>
	<p><?= Yii::t('app', 'View file'); ?><br>
	<?= Html::dropDownList('viewfile', $viewfile, $viewfiles, ['id'=>'viewfile']); ?></p>

  <p><?= Yii::t('app', 'Data'); ?>: <scan id="filename"></scan><br>
  <?= Html::textarea('filedata', $filedata, ['id'=>'filedata', 'rows'=>15, 'cols'=>120]); ?></p>

  <p><?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('cruds', 'Save'),
    [
      'id' => 'save-filedata',
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
$('#viewfile').on('click', function() {
  try {
    var viewfile = $(this).val();
    console.log('viewfile=['+viewfile+']');
    if (viewfile.length>0) {
      $.post(
        '/site/getfiledata',
        {'viewfile':viewfile}, (response) => {
          var data=JSON.parse(response);
          $('#filedata').val(data.filedata);
					$('#filename').text(viewfile);
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


