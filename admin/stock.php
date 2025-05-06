<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}


$sql = "SELECT 
            s.stock_id,
            s.product_id,
            p.name AS product_name,
            s.quantity,
            s.warehouse_id,
            w.name AS warehouse_name
        FROM ims_stock s
        JOIN ims_product p ON s.product_id = p.product_id
        JOIN ims_warehouse w ON s.warehouse_id = w.warehouse_id";
$result = mysqli_query($conn, $sql);

// Fetch products
$product_result = mysqli_query($conn, "SELECT product_id, name FROM ims_product ORDER BY name ASC");

// Fetch warehouses
$warehouse_result = mysqli_query($conn, "SELECT warehouse_id, name FROM ims_warehouse ORDER BY name ASC");

// $result = mysqli_query($conn, "SELECT * FROM Ims_stock ORDER BY stock_id ASC");
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
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Warehouse</th>
                    <th>Safety Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <?php $row_class = ($row['quantity'] <= 20) ? 'table-danger' : ''; ?>
                <tr class="<?= $row_class ?>" data-id="<?= $row['stock_id'] ?>" data-product="<?= $row['product_id'] ?>" data-qty="<?= $row['quantity'] ?>" data-warehouse="<?= $row['warehouse_id'] ?>">
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                    <td>20</td>
                    <td>
                        <a href="#" class="text-warning editBtn" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-pen"></i></a>
                        <a href="#" class="text-danger deleteBtn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"><i class="fa fa-trash"></i></a>
                    </td>
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
        <div class="mb-3">
          <label>Product</label>
          <select name="product_id" class="form-select" required>
            <option value="" disabled selected>Select Product</option>
            <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
              <option value="<?= $product['product_id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
          <!-- <div class="mb-3"><label>Product ID</label><input type="number" class="form-control" name="product_id" required></div> -->
          <div class="mb-3"><label>Quantity</label><input type="number" class="form-control" name="quantity" required></div>
          <!-- <div class="mb-3"><label>Warehouse ID</label><input type="number" class="form-control" name="warehouse_id" required></div> -->
          <div class="mb-3">
          <label>Warehouse</label>
          <select name="warehouse_id" class="form-select" required>
            <option value="" disabled selected>Select Warehouse</option>
            <?php while ($warehouse = mysqli_fetch_assoc($warehouse_result)): ?>
              <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5></div>
      <div class="modal-body">Are you sure you want to delete this stock item?</div>
      <div class="modal-footer">
        <a href="#" id="deleteLink" class="btn btn-danger">Delete</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
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

document.querySelectorAll('.deleteBtn').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.closest('tr').dataset.id;
    document.getElementById('deleteLink').href = 'crud_stock.php?delete=' + id;
  });
});
</script>
</body>
</html>
