<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM ims_stock ORDER BY stock_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../css/sidebar.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <h2>Stock</h2>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus"></i> Add Stock
    </button>

    <div class="input-group input-group-sm mb-3" style="max-width:250px;">
        <span class="input-group-text"><i class="fa fa-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by product or warehouse ID...">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="stockTable">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Warehouse ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr data-id="<?= $row['stock_id'] ?>" data-product="<?= $row['product_id'] ?>" data-qty="<?= $row['quantity'] ?>" data-warehouse="<?= $row['warehouse_id'] ?>">
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= htmlspecialchars($row['warehouse_id']) ?></td>
                    <td>
                        <a href="#" class="text-warning editBtn" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-pen"></i></a>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="crud_stock.php">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Stock</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Product ID</label><input type="number" class="form-control" name="product_id" required></div>
          <div class="mb-3"><label>Quantity</label><input type="number" class="form-control" name="quantity" required></div>
          <div class="mb-3"><label>Warehouse ID</label><input type="number" class="form-control" name="warehouse_id" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add" class="btn btn-success">Add</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="crud_stock.php">
      <input type="hidden" name="stock_id" id="edit-id">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Stock</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Product ID</label><input type="number" class="form-control" name="product_id" id="edit-product" required></div>
          <div class="mb-3"><label>Quantity</label><input type="number" class="form-control" name="quantity" id="edit-qty" required></div>
          <div class="mb-3"><label>Warehouse ID</label><input type="number" class="form-control" name="warehouse_id" id="edit-warehouse" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-warning">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/sidebar.js"></script>
<script src="../js/search.js"></script>

<script>
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', function () {
    const row = this.closest('tr');
    document.getElementById('edit-id').value = row.dataset.id;
    document.getElementById('edit-product').value = row.dataset.product;
    document.getElementById('edit-qty').value = row.dataset.qty;
    document.getElementById('edit-warehouse').value = row.dataset.warehouse;
  });
});
</script>

</body>
</html>
