<?php

/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = 'Gestão de Estoque Inteligente';
?>
<div class="site-index">

    <!-- Hero Section -->
    <div class="p-5 mb-5 rounded shadow-sm text-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
        <h1 class="display-4 fw-bold mb-3">Bem-vindo ao Controle de Estoque</h1>
        <p class="lead fs-4">Gerencie as entradas e saídas do seu negócio de forma rápida, centralizada e absolutamente precisa.</p>
        <hr class="my-4 border-light opacity-25">
        <div class="mt-4">
            <a href="<?= Url::to(['site/products']) ?>" class="btn btn-light btn-lg text-primary fw-bold mx-2 shadow-sm">
                Acessar Produtos
            </a>
            <a href="<?= Url::to(['site/sales']) ?>" class="btn btn-outline-light btn-lg mx-2">
                Acessar Vendas
            </a>
        </div>
    </div>

    <div class="body-content mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-secondary">Como funciona?</h2>
            <p class="text-muted">A nossa plataforma trabalha com lançamentos de precisão, interligando Vendas e Estoque.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm transition-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                1
                            </span>
                        </div>
                        <h4 class="fw-bold">Lançamento Base</h4>
                        <p class="text-muted">Adicione saldo positivo de estoque na listagem de seus produtos. Tudo é registrado sob uma transação rastreável.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm transition-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                2
                            </span>
                        </div>
                        <h4 class="fw-bold">Saída Dinâmica</h4>
                        <p class="text-muted">Ao lançar uma Venda, o sistema valida preventivamente os saldos e abate o quantitativo correto de forma automática.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm transition-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle" style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                3
                            </span>
                        </div>
                        <h4 class="fw-bold">Acompanhamento</h4>
                        <p class="text-muted">Acompanhe a situação atualizada por componente, reduzindo significativamente a perda de dados e evitando vendas impossíveis.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 bg-light p-5 rounded">
            <h3 class="fw-bold mb-4">Vantagens do Nosso Sistema</h3>
            <ul class="list-unstyled fs-5">
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Prevenção de Furos: Bloqueio sistêmico na tentativa de registrar vendas maiores do que o estoque correspondente.
                </li>
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Fluxo Otimizado: Menu esquerdo dedicado para alternância rápida entre operações comerciais.
                </li>
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Tecnologias Robustas: Integração fluída usando processamento da API de gestão com respostas instantâneas na grid.
                </li>
            </ul>
        </div>

    </div>
</div>

<style>
.transition-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
