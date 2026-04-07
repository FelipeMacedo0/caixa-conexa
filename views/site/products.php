<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <div class="mb-3">
        <?= Html::beginForm(['site/products'], 'get', ['class' => 'd-inline-flex align-items-center']) ?>
            <label for="limit" class="me-2">Exibir:</label>
            <?= Html::dropDownList('limit', $limit, [5 => 5, 10 => 10, 20 => 20, 50 => 50], [
                'id' => 'limit', 
                'onchange' => 'this.form.submit()', 
                'class' => 'form-select form-select-sm w-auto me-2'
            ]) ?>
            <span>itens</span>
            <?= Html::hiddenInput('offset', 0) ?>
        <?= Html::endForm() ?>
    </div>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products->data as $product): ?>
                <tr>
                    <td><?= Html::encode($product->productId) ?></td>
                    <td><?= Html::encode($product->name) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center">
                    <?php if ($offset > 0): ?>
                        <?= Html::a('Anterior', ['site/products', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($products->pagination) && $products->pagination->hasNext): ?>
                        <?= Html::a('Próxima', ['site/products', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
