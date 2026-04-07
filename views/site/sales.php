<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sales">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
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
        
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saleModal">
            Lançar Venda
        </button>
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

<!-- Modal para lançar venda -->
<div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="saleModalLabel">Lançar Venda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?= Html::beginForm(['site/launch-sale'], 'post') ?>
      <div class="modal-body">
          <div class="mb-3">
              <label for="modal_product_id" class="form-label">Produto</label>
              <select name="product_id" id="modal_product_id" class="form-select" required="required" style="width: 100%;">
                 <!-- select2 ajax options will be loaded here -->
              </select>
          </div>
          <div class="mb-3">
              <label for="modal_qtd" class="form-label">Quantidade</label>
              <?= Html::input('number', 'qtd', '', ['class' => 'form-control', 'id' => 'modal_qtd', 'required' => true, 'min' => 1]) ?>
          </div>
          <div class="mb-3">
              <label for="modal_observation" class="form-label">Observação (Opcional)</label>
              <?= Html::textarea('observation', '', ['class' => 'form-control', 'id' => 'modal_observation', 'rows' => 3]) ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar Venda</button>
      </div>
      <?= Html::endForm() ?>
    </div>
  </div>
</div>

<?php
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$urlSearch = \yii\helpers\Url::to(['site/search-products']);
$js = <<<JS
$(document).ready(function() {
    $('#modal_product_id').select2({
        dropdownParent: $('#saleModal'),
        theme: 'default',
        ajax: {
            url: '{$urlSearch}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        placeholder: 'Selecione um produto...',
        allowClear: true
    });
});
JS;
$this->registerJs($js);
?>
