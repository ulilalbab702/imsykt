<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM ims_warehouse ORDER BY warehouse_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warehouse - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../css/sidebar.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <h2>Warehouse List</h2>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus"></i> Add Warehouse
    </button>

    <div class="input-group input-group-sm mb-3" style="max-width:250px;">
        <span class="input-group-text"><i class="fa fa-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by name or location...">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Warehouse ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr data-id="<?= $row['warehouse_id'] ?>"
                    data-name="<?= htmlspecialchars($row['name']) ?>"
                    data-location="<?= htmlspecialchars($row['location']) ?>">
                    <td><?= htmlspecialchars($row['warehouse_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
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
    <form method="POST" action="crud_warehouse.php">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Warehouse</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Name</label><input type="text" class="form-control" name="name" required></div>
          <div class="mb-3"><label>Location</label><textarea class="form-control" name="location" required></textarea></div>
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
    <form method="POST" action="crud_warehouse.php">
      <input type="hidden" name="warehouse_id" id="edit-id">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Warehouse</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Name</label><input type="text" class="form-control" name="name" id="edit-name" required></div>
          <div class="mb-3"><label>Location</label><textarea class="form-control" name="location" id="edit-location" required></textarea></div>
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
      <div class="modal-body">Are you sure you want to delete this warehouse?</div>
      <div class="modal-footer">
        <a href="#" id="deleteLink" class="btn btn-danger">Delete</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<script src="../js/sidebar.js"></script>
<script src="../js/logout-confirm.js"></script>
<script src="../js/search.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', function () {
    const row = this.closest('tr');
    document.getElementById('edit-id').value = row.dataset.id;
    document.getElementById('edit-name').value = row.dataset.name;
    document.getElementById('edit-location').value = row.dataset.location;
  });
});

document.querySelectorAll('.deleteBtn').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.closest('tr').dataset.id;
    document.getElementById('deleteLink').href = 'crud_warehouse.php?delete=' + id;
  });
});
</script>
</body>
</html>
