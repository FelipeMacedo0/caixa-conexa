<?php
/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = 'Intelligent Stock Management';
?>
<div class="site-index">

    <!-- Hero Section -->
    <div class="p-5 mb-5 rounded shadow-sm text-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
        <h1 class="display-4 fw-bold mb-3">Welcome to Stock Wise</h1>
        <p class="lead fs-4">Manage your business inputs and outputs quickly, centrally, and with absolute precision.</p>
        <hr class="my-4 border-light opacity-25">
        <div class="mt-4">
            <a href="<?= Url::to(['site/products']) ?>" class="btn btn-light btn-lg text-primary fw-bold mx-2 shadow-sm">
                Access Products
            </a>
            <a href="<?= Url::to(['site/sales']) ?>" class="btn btn-outline-light btn-lg mx-2">
                Access Sales
            </a>
        </div>
    </div>

    <div class="body-content mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-secondary">How does it work?</h2>
            <p class="text-muted">Our platform works with precision tracking, interconnecting Sales and Inventory.</p>
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
                        <h4 class="fw-bold">Base Tracking</h4>
                        <p class="text-muted">Add positive stock balance to your product listing. Everything is recorded under a traceable transaction.</p>
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
                        <h4 class="fw-bold">Dynamic Output</h4>
                        <p class="text-muted">When recording a Sale, the system preemptively validates balances and deducts the correct quantity automatically.</p>
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
                        <h4 class="fw-bold">Monitoring</h4>
                        <p class="text-muted">Track the updated status per component, significantly reducing data loss and avoiding impossible sales.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 bg-light p-5 rounded">
            <h3 class="fw-bold mb-4">Advantages of Our System</h3>
            <ul class="list-unstyled fs-5">
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Gap Prevention: Systemic blocking when attempting to register sales greater than the corresponding inventory.
                </li>
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Optimized Flow: Dedicated sidebar for quick switching between business operations.
                </li>
                <li class="mb-3">
                    <strong class="text-primary">✓</strong> Robust Technologies: Fluid integration using management API processing with instantaneous grid responses.
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
