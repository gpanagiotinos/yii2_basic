<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($info, 'firstname') ?>
	<?= $form->field($info, 'lastname') ?>
    <?= $form->field($user, 'username')->textInput()->label('Username') ?>

    <?= $form->field($user, 'email') ?>

    <?= $form->field($user, 'password')->passwordInput() ?>
    <?= $form->field($user, 'role_id')->dropdownList($roles) ?>
    <?= $form->field($user, 'active')->checkbox([['label'=>'Active','checked'=>'1','uncheck'=>'0','value'=>'1']])?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>