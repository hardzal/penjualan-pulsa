<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    $query = "INSERT INTO penjualan(tanggal, pelanggan, pulsa, jumlah_bayar) VALUES(?, ?, ?, ?)";

    $stmt = $mysqli->prepare($query);

    $tanggal        = trim(date("Y-m-d", strtotime($_POST['tanggal'])));
    $pelanggan      = trim($_POST['id_pelanggan']);
    $pulsa          = trim($_POST['id_pulsa']);
    $jumlah_bayar   = trim($_POST['harga']);

    $stmt->bind_param("siii", $tanggal, $pelanggan, $pulsa, $jumlah_bayar);

    $stmt->execute();

    if($stmt) {
        echo "sukses";
    } else {
        echo "gagal";
    }

    $stmt->close();

    $mysqli->close();
} else {
    echo "<script>window.location='../../index.php'</script>";
}