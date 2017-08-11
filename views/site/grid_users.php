<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $grid_users,
    'columns' => [
        'email',
        'username',
    ],
]) ?>