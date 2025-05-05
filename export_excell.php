<?php
include 'config/config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=transaction_report.xls");

echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>Product Name</th>
            <th>Transaction Type</th>
            <th>Quantity</th>
            <th>Transaction Date</th>
        </tr>";

$query = mysqli_query($conn, "SELECT t.*, p.name AS product_name 
                                 FROM ims_transaction t 
                                 JOIN ims_product p ON t.product_id = p.product_id 
                                 ORDER BY t.transaction_date DESC");

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['product_name']}</td>
            <td>" . strtoupper($row['type']) . "</td>
            <td>{$row['quantity']}</td>
            <td>{$row['transaction_date']}</td>
          </tr>";
    $no++;
}
echo "</table>";
?>
