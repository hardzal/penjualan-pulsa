<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    $query = "SELECT sum(jumlah_bayar) as total FROM penjualan";

    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Query Error: " . $mysqli->error . '-' . $mysqli->error);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $data = $result->fetch_assoc();

    echo "Rp." . number_format($data['total']);

    $stmt->close();

    $mysqli->close();
} else {
    echo '<script>window.location="../../index.php"</script>';
}
