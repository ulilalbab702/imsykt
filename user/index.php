<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../css/sidebar.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<?php
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ims_product"))['total'];
$totalSuppliers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ims_supplier"))['total'];
$totalTransactions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ims_transaction"))['total'];
$totalWarehouses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ims_warehouse"))['total'];

$stockData = mysqli_query($conn, "
    SELECT 
        DATE(transaction_date) as date,
        SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) AS incoming,
        SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) AS outgoing
    FROM ims_transaction
    GROUP BY DATE(transaction_date)
    ORDER BY date DESC
    LIMIT 7
");

$dates = $incoming = $outgoing = [];
while ($row = mysqli_fetch_assoc($stockData)) {
    $dates[] = $row['date'];
    $incoming[] = $row['incoming'];
    $outgoing[] = $row['outgoing'];
}

$recentTransactions = mysqli_query($conn, "
    SELECT t.*, p.name AS product_name 
    FROM ims_transaction t 
    JOIN ims_product p ON t.product_id = p.product_id 
    ORDER BY transaction_date DESC 
    LIMIT 5
");
?>

<!-- Bootstrap Icons & Chart.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .main-container {
        margin-left: 250px;
        padding: 20px;
    }
    @media (max-width: 768px) {
        .main-container {
            margin-left: 0;
        }
    }
    .info-box-small {
        padding: 10px;
        font-size: 0.85rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
    }
    .info-box-small i {
        font-size: 1.5rem;
        margin-right: 8px;
    }
    .fade-in {
        opacity: 0;
        transform: translateY(-10px);
        animation: fadeInSlideDown 1s ease forwards;
    }

    @keyframes fadeInSlideDown {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="main-container">
    
    <div class="container mt-2 d-flex justify-content-center fade-in" style="max-width: 920px;">
        <div class="w-100 bg-light text-dark shadow-sm d-flex align-items-center justify-content-center rounded py-2 px-3" style="font-size: 0.95rem;">
            <i class="bi bi-person-badge-fill me-2"></i>
            <strong>Welcome, Staff!</strong>
        </div>
    </div>

    <!-- Info Boxes -->
    <div class="container mt-3 d-flex justify-content-center" style="max-width: 920px;">
        <div class="row text-center g-3 w-100">
            <div class="col-6 col-md-3">
                <div class="bg-light text-dark shadow-sm info-box-small">
                    <i class="bi bi-box-seam"></i>
                    <div>
                        <div><small>Products</small></div>
                        <div><strong><?= $totalProducts ?></strong></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-light text-dark shadow-sm info-box-small">
                    <i class="bi bi-people-fill"></i>
                    <div>
                        <div><small>Suppliers</small></div>
                        <div><strong><?= $totalSuppliers ?></strong></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-light text-dark shadow-sm info-box-small">
                    <i class="bi bi-repeat"></i>
                    <div>
                        <div><small>Transactions</small></div>
                        <div><strong><?= $totalTransactions ?></strong></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-light text-dark shadow-sm info-box-small">
                    <i class="bi bi-house-door-fill"></i>
                    <div>
                        <div><small>Warehouses</small></div>
                        <div><strong><?= $totalWarehouses ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Chart -->
    <div class="container mt-4 d-flex justify-content-center" style="max-width: 920px;">
        <div class="card w-100">
            <div class="card-body">
                <h6 class="card-title">Incoming vs Outgoing Stock (Last 7 Days)</h6>
                <canvas id="stockChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="container mt-4 d-flex justify-content-center" style="max-width: 920px;">
        <div class="card w-100">
            <div class="card-body">
                <h6 class="card-title">5 Latest Transactions</h6>
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($t = mysqli_fetch_assoc($recentTransactions)): ?>
                            <tr>
                                <td><?= $t['transaction_date'] ?></td>
                                <td><?= $t['product_name'] ?></td>
                                <td><?= ucfirst($t['type']) ?></td>
                                <td><?= $t['quantity'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Chart Script -->
<script>
const ctx = document.getElementById('stockChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_reverse($dates)) ?>,
        datasets: [
            {
                label: 'Incoming Stock',
                data: <?= json_encode(array_reverse($incoming)) ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0, 128, 0, 0.1)',
                fill: true
            },
            {
                label: 'Outgoing Stock',
                data: <?= json_encode(array_reverse($outgoing)) ?>,
                borderColor: 'red',
                backgroundColor: 'rgba(255, 0, 0, 0.1)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
        }
    }
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/sidebar.js"></script>
</body>
</html>