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
            <?= Html::button('<i class="fas fa-user-friends"></i> Sync People', [
                'id' => 'btn-import-persons',
                'class' => 'btn btn-outline-primary me-2',
            ]) ?>
            <?= Html::button('<i class="fas fa-sync"></i> Sync Customers', [
                'id' => 'btn-import-customers',
                'class' => 'btn btn-primary me-2',
            ]) ?>
            <?= Html::button('<i class="fas fa-shopping-cart"></i> Sync Sales', [
                'id' => 'btn-import-sales',
                'class' => 'btn btn-outline-success me-2',
            ]) ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saleModal">
                Register Sale
            </button>
        </div>
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
                        <?= Html::a('Previous', ['site/sales', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($sales->pagination)): ?>
                        <?php if ($sales->pagination->hasNext): ?>
                            <?= Html::a('Next', ['site/sales', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        <?php elseif ($offset > 0): ?>
                            <?= Html::a('First page', ['site/sales', 'limit' => $limit, 'offset' => 0], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Register sale modal -->
<div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="saleModalLabel">Register Sale</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?= Html::beginForm(['site/store-sale'], 'post') ?>
      <div class="modal-body">
          <div class="mb-3">
              <label for="modal_product_id" class="form-label">Product</label>
              <select name="product_id" id="modal_product_id" class="form-select" required="required" style="width: 100%;">
                 <!-- select2 ajax options will be loaded here -->
              </select>
          </div>
          <div class="mb-3">
              <label for="modal_customer_id" class="form-label">Customer</label>
              <select name="customer_id" id="modal_customer_id" class="form-select" required="required" style="width: 100%;">
                 <!-- select2 ajax options will be loaded here -->
              </select>
          </div>
          <div class="mb-3">
              <label for="modal_requester_id" class="form-label">Person (Requester)</label>
              <select name="requester_id" id="modal_requester_id" class="form-select" style="width: 100%;">
                 <!-- select2 ajax options will be loaded here -->
              </select>
          </div>
          <div class="mb-3">
              <label for="modal_qtd" class="form-label">Quantity</label>
              <?= Html::input('number', 'qtd', '', ['class' => 'form-control', 'id' => 'modal_qtd', 'required' => true, 'min' => 1]) ?>
          </div>
          <div class="mb-3">
              <label for="modal_observation" class="form-label">Note (Optional)</label>
              <?= Html::textarea('observation', '', ['class' => 'form-control', 'id' => 'modal_observation', 'rows' => 3]) ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Sale</button>
      </div>
      <?= Html::endForm() ?>
    </div>
  </div>
</div>

<?php
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$urlSearchProducts = \yii\helpers\Url::to(['site/search-products']);
$urlSearchCustomers = \yii\helpers\Url::to(['site/search-customers']);
$urlSearchPersons = \yii\helpers\Url::to(['site/search-persons']);
$urlImportCustomers = \yii\helpers\Url::to(['site/import-customers']);
$urlImportPersons = \yii\helpers\Url::to(['site/import-persons']);
$urlImportSales = \yii\helpers\Url::to(['site/import-sales']);

$js = <<<JS
var btnImportCustomers = document.getElementById('btn-import-customers');
if (btnImportCustomers) {
    btnImportCustomers.addEventListener('click', function() {
        if (!confirm('Do you want to start syncing customers with the Conexa API?')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Syncing...';

        fetch("{$urlImportCustomers}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Customer sync started successfully and is running in the background.');
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
                    btn.innerHTML = '<i class="fas fa-sync"></i> Sync Customers';
                }, 3000);
            });
    });
}

var btnImportPersons = document.getElementById('btn-import-persons');
if (btnImportPersons) {
    btnImportPersons.addEventListener('click', function() {
        if (!confirm('Do you want to start syncing people with the Conexa API?')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Syncing...';

        fetch("{$urlImportPersons}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('People sync started successfully and is running in the background.');
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
                    btn.innerHTML = '<i class="fas fa-user-friends"></i> Sync People';
                }, 3000);
            });
    });
}

var btnImportSales = document.getElementById('btn-import-sales');
if (btnImportSales) {
    btnImportSales.addEventListener('click', function() {
        if (!confirm('Do you want to start syncing sales with the Conexa API?')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Syncing...';

        fetch("{$urlImportSales}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sales sync started successfully and is running in the background.');
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
                    btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Sync Sales';
                }, 3000);
            });
    });
}

$(document).ready(function() {
    $('#modal_product_id').select2({
        dropdownParent: $('#saleModal'),
        theme: 'default',
        ajax: {
            url: '{$urlSearchProducts}',
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
        placeholder: 'Select a product...',
        allowClear: true
    });

    $('#modal_customer_id').select2({
        dropdownParent: $('#saleModal'),
        theme: 'default',
        ajax: {
            url: '{$urlSearchCustomers}',
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
        placeholder: 'Select a customer...',
        allowClear: true
    }).on('change', function() {
        // Reset requester when customer changes
        $('#modal_requester_id').val(null).trigger('change');
    });

    $('#modal_requester_id').select2({
        dropdownParent: $('#saleModal'),
        theme: 'default',
        ajax: {
            url: '{$urlSearchPersons}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    customer_id: $('#modal_customer_id').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        placeholder: 'Select a person (requester)...',
        allowClear: true
    });
});
JS;
$this->registerJs($js);
?>
