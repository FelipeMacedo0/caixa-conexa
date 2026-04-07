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
                <th class="text-center" style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products->data as $product): ?>
                <tr>
                    <td class="align-middle"><?= Html::encode($product->productId) ?></td>
                    <td class="align-middle"><?= Html::encode($product->name) ?></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#stockModal" data-product-id="<?= Html::encode($product->productId) ?>">
                            Lançar Estoque
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center">
                    <?php if ($offset > 0): ?>
                        <?= Html::a('Anterior', ['site/products', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($products->pagination)): ?>
                        <?php if ($products->pagination->hasNext): ?>
                            <?= Html::a('Próxima', ['site/products', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        <?php elseif ($offset > 0): ?>
                            <?= Html::a('Primeira página', ['site/products', 'limit' => $limit, 'offset' => 0], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Modal para lançar estoque -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stockModalLabel">Lançar Estoque</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?= Html::beginForm(['site/add-stock'], 'post') ?>
      <div class="modal-body">
          <?= Html::hiddenInput('product_id', '', ['id' => 'modal_product_id']) ?>
          <div class="mb-3">
              <label for="modal_qtd" class="form-label">Quantidade</label>
              <?= Html::input('number', 'qtd', '', ['class' => 'form-control', 'id' => 'modal_qtd', 'required' => true]) ?>
          </div>
          <div class="mb-3">
              <label for="modal_observation" class="form-label">Observação (Opcional)</label>
              <?= Html::textarea('observation', '', ['class' => 'form-control', 'id' => 'modal_observation', 'rows' => 3]) ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
      <?= Html::endForm() ?>
    </div>
  </div>
</div>

<?php
$js = <<<JS
var stockModal = document.getElementById('stockModal')
if (stockModal) {
    stockModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var productId = button.getAttribute('data-product-id')
        var inputProductId = stockModal.querySelector('#modal_product_id')
        inputProductId.value = productId;
    })
}
JS;
$this->registerJs($js);
?>
