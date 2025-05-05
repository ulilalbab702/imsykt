<?php
include '../config/config.php';
date_default_timezone_set('Asia/Jakarta');

    // update stock di ims_stock
function updateStock($conn, $product_id, $quantity, $type) {
    // Hitung quantity penyesuaian
    $adjust_qty = ($type === 'in') ? $quantity : -$quantity;
    // Cek product_id ada enggak di ims_stock
    $check = $conn->prepare("SELECT quantity FROM ims_stock WHERE product_id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        // Update stock
        $update = $conn->prepare("UPDATE ims_stock SET quantity = quantity + ? WHERE product_id = ?");
        $update->bind_param("ii", $adjust_qty, $product_id);
        $update->execute();
    } else {
        // Insert new stock
        $insert = $conn->prepare("INSERT INTO ims_stock (product_id, quantity) VALUES (?, ?)");
        $insert->bind_param("ii", $product_id, $adjust_qty);
        $insert->execute();
    }
}

// Fungsi untuk mengembalikan stock jika transaksi di-edit atau dihapus
function reverseStock($conn, $transaction_id) {
    $stmt = $conn->prepare("SELECT product_id, quantity, type FROM ims_transaction WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $type = $row['type'];
        $reverse_qty = ($type === 'in') ? -$quantity : $quantity;

        $update = $conn->prepare("UPDATE ims_stock SET quantity = quantity + ? WHERE product_id = ?");
        $update->bind_param("ii", $reverse_qty, $product_id);
        $update->execute();
    }
}

// Add
if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];

    $date = $_POST['transaction_date'];
    $time = date("H:i:s"); // waktu saat ini
    $transaction_date = $date . ' ' . $time;
    $transaction_date = $_POST['transaction_date'];
    $warehouse_id = $_POST['warehouse_id'];

    $stmt = $conn->prepare("INSERT INTO ims_transaction (product_id, type, quantity, transaction_date, warehouse_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isisi", $product_id, $type, $quantity, $transaction_date, $warehouse_id);
    $stmt->execute();

    updateStock($conn, $product_id, $quantity, $type);

    header("Location: transaction.php");
    exit;
}

// Edit
if (isset($_POST['edit'])) {
    $transaction_id = $_POST['transaction_id'];
    $product_id = $_POST['product_id'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $date = $_POST['transaction_date'];
    $time = date("H:i:s");
    $transaction_date = $date . ' ' . $time;
    $warehouse_id = $_POST['warehouse_id'];

    // Kembalikan stok sebelumnya
    reverseStock($conn, $transaction_id);

    // Update transaksi
    $stmt = $conn->prepare("UPDATE ims_transaction SET product_id=?, type=?, quantity=?, transaction_date=?, warehouse_id=? WHERE transaction_id=?");
    $stmt->bind_param("isissi", $product_id, $type, $quantity, $transaction_date, $warehouse_id, $transaction_id);
    $stmt->execute();

    // Update stok baru
    updateStock($conn, $product_id, $quantity, $type);

    header("Location: transaction.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $transaction_id = intval($_GET['delete']);

    reverseStock($conn, $transaction_id);

    // delete transaksi
    $conn->query("DELETE FROM ims_transaction WHERE transaction_id = $transaction_id");

    $conn->query("SET @new_id = 0");
    $conn->query("UPDATE ims_transaction SET transaction_id = (@new_id := @new_id + 1)");
    $conn->query("ALTER TABLE ims_transaction AUTO_INCREMENT = 1");

    header("Location: transaction.php");
    exit;
}
?>