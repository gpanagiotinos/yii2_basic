<?php
use yii\helpers\Html;
?>
<h1>Our Users</h1>
<ul>
    <li>
	    <label>Username</label>: <?= Html::encode($model->username) ?>
	    <label>Email</label>: <?= Html::encode($model->email) ?>
    </li>
</ul>