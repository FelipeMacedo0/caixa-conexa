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
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <?= Html::beginForm(['site/products'], 'get', ['class' => 'd-inline-flex align-items-center']) ?>
                <label for="limit" class="me-2">Display:</label>
                <?= Html::dropDownList('limit', $limit, [5 => 5, 10 => 10, 20 => 20, 50 => 50], [
                    'id' => 'limit', 
                    'onchange' => 'this.form.submit()', 
                    'class' => 'form-select form-select-sm w-auto me-2'
                ]) ?>
                <span>items</span>
                <?= Html::hiddenInput('offset', 0) ?>
            <?= Html::endForm() ?>
        </div>
        <div>
            <?= Html::button('<i class="fas fa-sync"></i> Sync Products', [
                'id' => 'btn-import-products',
                'class' => 'btn btn-primary',
            ]) ?>
        </div>
    </div>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Inventory</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products->data as $product): ?>
                <?php
                $stock = (int)\app\models\ProductStock::getStockBalance($product->productId);
                if ($stock <= 0) {
                    $stockStyle = 'color: red; font-weight: bold;';
                } elseif ($stock < 10) {
                    $stockStyle = 'color: orange; font-weight: bold;';
                } elseif ($stock < 50) {
                    $stockStyle = 'color: #d39e00; font-weight: bold;'; // Dark yellow for readability
                } else {
                    $stockStyle = 'color: green; font-weight: bold;';
                }
                ?>
                <tr>
                    <td class="align-middle"><?= Html::encode($product->productId) ?></td>
                    <td class="align-middle"><?= Html::encode($product->name) ?></td>
                    <td class="align-middle" style="<?= $stockStyle ?>"><?= Html::encode($stock) ?></td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#stockModal" data-product-id="<?= Html::encode($product->productId) ?>" data-product-name="<?= Html::encode($product->name) ?>">
                                        <i class="fas fa-plus-circle"></i> Add Inventory
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#historyModal" data-product-id="<?= Html::encode($product->productId) ?>" data-product-name="<?= Html::encode($product->name) ?>">
                                        <i class="fas fa-history"></i> View History
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">
                    <?php if ($offset > 0): ?>
                        <?= Html::a('Previous', ['site/products', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($products->pagination)): ?>
                        <?php if ($products->pagination->hasNext): ?>
                            <?= Html::a('Next', ['site/products', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        <?php elseif ($offset > 0): ?>
                            <?= Html::a('First page', ['site/products', 'limit' => $limit, 'offset' => 0], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Add inventory modal -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stockModalLabel">Add Inventory: <span id="stock_product_name"></span> (ID: <span id="stock_product_id_display"></span>)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?= Html::beginForm(['site/add-stock'], 'post') ?>
      <div class="modal-body">
          <?= Html::hiddenInput('product_id', '', ['id' => 'modal_product_id']) ?>
          <div class="mb-3">
              <label for="modal_qtd" class="form-label">Quantity</label>
              <?= Html::input('number', 'qtd', '', ['class' => 'form-control', 'id' => 'modal_qtd', 'required' => true]) ?>
          </div>
          <div class="mb-3">
              <label for="modal_observation" class="form-label">Note (Optional)</label>
              <?= Html::textarea('observation', '', ['class' => 'form-control', 'id' => 'modal_observation', 'rows' => 3]) ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      <?= Html::endForm() ?>
    </div>
  </div>
</div>

<!-- Inventory History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">Movement History: <span id="history_product_name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div id="history_loading" class="text-center d-none">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
              </div>
          </div>
          <div class="table-responsive">
              <table class="table table-sm table-hover" id="history_table">
                  <thead>
                      <tr>
                          <th>Date</th>
                          <th>Type</th>
                          <th class="text-end">Qty</th>
                          <th>Note</th>
                      </tr>
                  </thead>
                  <tbody id="history_body">
                      <!-- Movements will be inserted here -->
                  </tbody>
              </table>
          </div>
          <div id="history_empty" class="alert alert-info d-none">
              No movements found for this product.
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
$urlImportProducts = \yii\helpers\Url::to(['site/import-products']);
$urlStockHistory = \yii\helpers\Url::to(['site/stock-history']);

$js = <<<JS
var btnImport = document.getElementById('btn-import-products');
if (btnImport) {
    btnImport.addEventListener('click', function() {
        if (!confirm('Do you want to start syncing products with the Conexa API?')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Syncing...';

        fetch("{$urlImportProducts}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sync started successfully and is running in the background.');
                } else {
                    alert('Error starting sync: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Request sent. Check status later.');
            })
            .finally(() => {
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync"></i> Sync Products';
                }, 3000);
            });
    });
}

var stockModal = document.getElementById('stockModal')
if (stockModal) {
    stockModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var productId = button.getAttribute('data-product-id')
        var productName = button.getAttribute('data-product-name')
        
        var inputProductId = stockModal.querySelector('#modal_product_id')
        inputProductId.value = productId;
        stockModal.querySelector('#stock_product_name').innerText = productName;
        stockModal.querySelector('#stock_product_id_display').innerText = productId;
    })
}

var historyModal = document.getElementById('historyModal')
if (historyModal) {
    historyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var productId = button.getAttribute('data-product-id')
        var productName = button.getAttribute('data-product-name')
        
        document.getElementById('history_product_name').innerText = productName;
        var tableBody = document.getElementById('history_body');
        var loading = document.getElementById('history_loading');
        var empty = document.getElementById('history_empty');
        var table = document.getElementById('history_table');
        
        tableBody.innerHTML = '';
        loading.classList.remove('d-none');
        empty.classList.add('d-none');
        table.classList.add('d-none');
        
        var url = "{$urlStockHistory}";
        var separator = url.indexOf('?') !== -1 ? '&' : '?';
        
        fetch(url + separator + "product_id=" + productId)
            .then(response => response.json())
            .then(response => {
                loading.classList.add('d-none');
                if (response.success && response.data.length > 0) {
                    table.classList.remove('d-none');
                    response.data.forEach(item => {
                        var badgeClass = (item.type === 'Addition' || item.type === 'Entrada') ? 'bg-success' : 'bg-danger';
                        var row = `<tr>
                            <td>\${item.date}</td>
                            <td><span class="badge \${badgeClass}">\${item.type}</span></td>
                            <td class="text-end">\${item.qtd}</td>
                            <td>\${item.observation}</td>
                        </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    empty.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loading.classList.add('d-none');
                alert('Error loading history: ' + error);
            });
    })
}
JS;
$this->registerJs($js);
?>
