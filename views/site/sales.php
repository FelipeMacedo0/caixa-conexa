<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sales">
    <div class="mb-3">
        <?= Html::beginForm(['site/sales'], 'get', ['class' => 'd-inline-flex align-items-center']) ?>
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
                <th>Product</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales->data as $sale): ?>
                <tr>
                    <td><?= Html::encode($sale->saleId) ?></td>
                    <td><?= Html::encode($sale->product ? $sale->product->name : '') ?></td>
                    <td><?= Html::encode($sale->status) ?></td>
                    <td><?= Html::encode($sale->quantity) ?></td>
                    <td><?= Html::encode($sale->amount) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    <?php if ($offset > 0): ?>
                        <?= Html::a('Anterior', ['site/sales', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($sales->pagination)): ?>
                        <?php if ($sales->pagination->hasNext): ?>
                            <?= Html::a('Próxima', ['site/sales', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        <?php elseif ($offset > 0): ?>
                            <?= Html::a('Primeira página', ['site/sales', 'limit' => $limit, 'offset' => 0], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
