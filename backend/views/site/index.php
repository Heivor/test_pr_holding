<?php

use common\models\apple\Apple;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/** @var yii\web\View $this */
/** @var \backend\models\AppleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Яблоки';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsVar('EAT_URL', Url::toRoute(['eat'],true), View::POS_HEAD);
$promptJs = <<<'JS'
    function inputEatenPercent(event,id) {
        let eatenPercent, attempt = 3;
        while (true) {
            eatenPercent = parseInt(prompt('Введите % съеденного'));
            if (Number.isInteger(eatenPercent) && (eatenPercent > 0) || (eatenPercent <= 100)) {
                const url = new URL(EAT_URL);
                url.searchParams.set('id', id);
                url.searchParams.set('eaten_percent', eatenPercent);
                window.location.href = url.toString();
                break;
            } else {
                event.preventDefault();
                alert("Пожалуйста введите правильное значение!");
                attempt--;
                if (attempt <= 0) {
                    break;
                }
            }
        }
    }
JS;
$this->registerJs($promptJs, View::POS_HEAD);

?>
<div class="index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать яблоки', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'krajeeDialogSettings' => [

        ],
        'export' => false,
        'columns' => [
            'id',
            'color',
            'eaten_percent',
            [
                'attribute' => 'status',
                'value' => function (Apple $model) use ($searchModel) {
                    return $searchModel->statusList[$model->status];
                },
                'filter' => false,
               // 'filter' => Html::activeDropDownList($searchModel, 'status',  $searchModel->statusList, ['class' => 'form-control', 'prompt' => 'Все']),
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:H:i:s d.m.Y'],
                'filter' => false,
            ],
            [
                'attribute' => 'fell_at',
                'format' => ['datetime', 'php:H:i:s d.m.Y'],
                'filter' => false,
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{fall} {eat}',
                'buttons' => [
                    'fall' => function ($url, Apple $model) {
                        return Html::a( 'На землю',Url::to(['fall','id' => $model->id]), ['title' => 'На землю']);
                    },
                    'eat' => function ($url, Apple $model) {
                        return Html::a( 'Съесть','#',
                            ['title' => 'Отменить заказ','data-pjax' => '0','id' => $model->id,'onclick' => new JsExpression('inputEatenPercent(event,this.id)')]);
                    },
                ],

            ],

        ]
    ]); ?>


</div>
