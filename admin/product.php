<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// $result = mysqli_query($conn, "SELECT * FROM ims_product ORDER BY product_id ASC");

$sql = "SELECT 
            p.product_id, 
            p.name, 
            p.description, 
            p.supplier_id, 
            s.name AS supplier_name,
            p.price, 
            p.image, 
            p.created_at
        FROM ims_product p
        JOIN ims_supplier s ON p.supplier_id = s.supplier_id";
$result = mysqli_query($conn, $sql);

$suppliers = mysqli_query($conn, "SELECT supplier_id, name FROM ims_supplier ORDER BY name ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../css/sidebar.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <h2>Product</h2>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus"></i> Add Product
    </button>

    <div class="input-group input-group-sm mb-3" style="max-width:250px;">
        <span class="input-group-text"><i class="fa fa-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by name...">
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr data-id="<?= $row['product_id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>" data-description="<?= htmlspecialchars($row['description']) ?>" data-supplier="<?= $row['supplier_id'] ?>" data-price="<?= $row['price'] ?>" data-image="<?= $row['image'] ?>" data-created="<?= $row['created_at'] ?>">
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= $row['supplier_name'] ?></td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['created_at'] ?></td>
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
        <div class="col-md-4">
            <div class="border p-3 bg-dark text-white rounded">
                <h5>Preview Product</h5>
                <img id="previewImage" src="" class="img-fluid mb-2" alt="Product Image" style="width: 250px; height: 160px; object-fit: cover;">
                <p><strong id="previewName"></strong></p>
                <p><strong>Description:</strong> <span id="previewDesc"></span></p>
                <p><strong>Supplier:</strong> <span id="previewSupplier"></span></p>
                <p><strong>Created:</strong> <span id="previewCreated"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="crud_product.php" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Product</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Name</label><input type="text" class="form-control" name="name" required></div>
          <div class="mb-3"><label>Description</label><textarea class="form-control" name="description"></textarea></div>
          <div class="mb-3"><label>Supplier</label>
          <select class="form-select" name="supplier_id" required>
            <option value="" disabled selected>Select Supplier</option>
            <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
              <option value="<?= $supplier['supplier_id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
            <?php endwhile; ?>
          </select>
            </div>
          <!-- <div class="mb-3"><label>Supplier</label><input type="number" class="form-control" name="supplier_id" required></div> -->
          <div class="mb-3"><label>Price</label><input type="number" class="form-control" name="price" step="0.01" required></div>
          <div class="mb-3"><label>Image</label><input type="file" class="form-control" name="image" accept="image/*"></div>
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
    <form method="POST" action="crud_product.php" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit-id">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Product</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Name</label><input type="text" class="form-control" name="name" id="edit-name" required></div>
          <div class="mb-3"><label>Description</label><textarea class="form-control" name="description" id="edit-description"></textarea></div>
          <div class="mb-3"><label>Supplier</label><input type="number" class="form-control" name="supplier_id" id="edit-supplier" required></div>
          <div class="mb-3"><label>Price</label><input type="number" class="form-control" name="price" id="edit-price" step="0.01" required></div>
          <div class="mb-3"><label>Image</label><input type="file" class="form-control" name="image" accept="image/*"></div>
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
      <div class="modal-body">Are you sure you want to delete this product?</div>
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
        document.getElementById('edit-name').value = row.dataset.name;
        document.getElementById('edit-description').value = row.dataset.description;
        document.getElementById('edit-supplier').value = row.dataset.supplier;
        document.getElementById('edit-price').value = row.dataset.price;
    });
});

document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.closest('tr').dataset.id;
        document.getElementById('deleteLink').href = 'crud_product.php?delete=' + id;
    });
});

document.querySelectorAll('tr').forEach(row => {
    row.addEventListener('click', function () {
        const img = row.dataset.image ? '../uploads/' + row.dataset.image : '';
        document.getElementById('previewImage').src = img;
        document.getElementById('previewName').textContent = row.dataset.name;
        document.getElementById('previewDesc').textContent = row.dataset.description;
        document.getElementById('previewSupplier').textContent = row.dataset.supplier;
        document.getElementById('previewCreated').textContent = row.dataset.created;
    });
});
</script>
</body>
</html>
