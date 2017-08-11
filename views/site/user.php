<?php
use yii\helpers\Html;
$url ='index.php?r=site/update&id='
?>
<h1>User Information</h1>
<ul>
	    <label>Username</label>: <?= Html::encode($current_user->username) ?>
	    <label>Email</label>: <?= Html::encode($current_user->email) ?>
	    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url.$current_user->id, [
                            'title' => Yii::t('app', 'update'),
                ]); ?>
</ul>