<div class="sidebar">
    <div class="text-center mb-4">
        <img src="../img/logo-ykt.png" alt="YKT" style="max-width: 100px;">
        <h5>YKT</h5>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                <i class="fa fa-home me-2"></i>Home
            </a>
        </li>
        <li class="nav-item"><a href="product.php" class="nav-link"><i class="fa fa-box me-2"></i>Product</a></li>
        <li class="nav-item"><a href="stock.php" class="nav-link"><i class="fa fa-cube me-2"></i>Stock</a></li>
        <li class="nav-item"><a href="warehouse.php" class="nav-link"><i class="fa fa-building me-2"></i>Warehouse</a></li>
        <li class="nav-item"><a href="supplier.php" class="nav-link"><i class="fa fa-users me-2"></i>Supplier</a></li>
        <li class="nav-item"><a href="transaction.php" class="nav-link"><i class="fa fa-exchange me-2"></i>Transaction</a></li>
        <li class="nav-item mt-3">
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa fa-sign-out-alt me-2"></i>Log Out
            </a>
        </li>
    </ul>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to exit?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <a href="../logout.php" class="btn btn-danger">Yes</a>
      </div>
    </div>
  </div>
</div>
