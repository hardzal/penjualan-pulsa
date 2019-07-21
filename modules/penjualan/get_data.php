<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if (isset($_GET['id_penjualan'])) {
        $query = "SELECT a.id_penjualan, a.tanggal, a.pelanggan, a.pulsa, a.jumlah_bayar, b.nama, b.no_hp, c.provider, c.nominal FROM penjualan AS a INNER JOIN pelanggan AS b INNER JOIN pulsa AS c ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa WHERE a.id_penjualan = ?";

        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            die("Query Error: " . $mysqli->errno . "-" . $mysqli->error);
        }

        $id_penjualan = trim($_GET['id_penjualan']);

        $stmt->bind_param("i", $id_penjualan);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();
    }

    $mysqli->close();

    echo json_encode($data);
} else {
    echo "<script>window.location='../../index.php'</script>";
}
