<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($info_updated, 'firstname') ?>
	<?= $form->field($info_updated, 'lastname') ?>
    <?= $form->field($user_updated, 'username')->textInput()->label('Username') ?>
    <?= $form->field($user_updated, 'email') ?>
    <?= $form->field($user_updated, 'role_id')->dropdownList($role_updated) ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>