<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $modelAnswer app\models\Answers */
/* @var $modelMessage app\models\Messages */

$this->title = $modelMessage->title;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-view">

    <?= DetailView::widget([
        'model' => $modelMessage,
        'attributes' => [
            'id',
            'title',
            'text:ntext',
            'status.name',
        ],
    ]);
    ?>

    <?if(!empty($answers)):?>
        <h3>Комментарии</h3>
        <?foreach($answers as $answer):?>
            <div>
                <b><?=$answer->text;?></b>
            </div>
        <?endforeach;?>

    <?endif;?>

    <?= $this->render('//answers/_form', [
        'model' => $modelAnswer,
    ]) ?>


</div>
