<?php

/** @var yii\web\View $this */
/** @var object $persons */
/** @var int $limit */
/** @var int $offset */

use yii\bootstrap5\Html;

$this->title = 'Persons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-persons">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <?= Html::beginForm(['site/persons'], 'get', ['class' => 'd-inline-flex align-items-center']) ?>
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
        <div>
            <?= Html::button('<i class="fas fa-sync"></i> Sincronizar Pessoas', [
                'id' => 'btn-import-persons',
                'class' => 'btn btn-primary',
            ]) ?>
        </div>
    </div>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>CPF</th>
                <th>Cell Number</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($persons->data as $person): ?>
                <tr>
                    <td class="align-middle"><?= Html::encode($person->personId) ?></td>
                    <td class="align-middle"><?= Html::encode($person->name) ?></td>
                    <td class="align-middle"><?= Html::encode($person->cpf) ?></td>
                    <td class="align-middle"><?= Html::encode($person->cellNumber) ?></td>
                    <td class="text-center">
                        <?php if ($person->isActive): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($persons->data)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Nenhuma pessoa encontrada. Clique em Sincronizar para buscar da API.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    <?php if ($offset > 0): ?>
                        <?= Html::a('Anterior', ['site/persons', 'limit' => $limit, 'offset' => max(0, $offset - $limit)], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php endif; ?>
                    
                    <?php if (isset($persons->pagination)): ?>
                        <?php if ($persons->pagination->hasNext): ?>
                            <?= Html::a('Próxima', ['site/persons', 'limit' => $limit, 'offset' => $offset + $limit], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        <?php elseif ($offset > 0): ?>
                            <?= Html::a('Primeira página', ['site/persons', 'limit' => $limit, 'offset' => 0], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
$urlImportPersons = \yii\helpers\Url::to(['site/import-persons']);

$js = <<<JS
var btnImport = document.getElementById('btn-import-persons');
if (btnImport) {
    btnImport.addEventListener('click', function() {
        if (!confirm('Deseja iniciar a sincronização de pessoas com a API Conexa?')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sincronizando...';

        fetch("{$urlImportPersons}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('A sincronização de pessoas foi iniciada com sucesso e está rodando em segundo plano.');
                } else {
                    alert('Erro ao iniciar sincronização: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('A requisição foi enviada. Verifique o status posteriormente.');
            })
            .finally(() => {
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync"></i> Sincronizar Pessoas';
                }, 3000);
            });
    });
}
JS;
$this->registerJs($js);
?>
