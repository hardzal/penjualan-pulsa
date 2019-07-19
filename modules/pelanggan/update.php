<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if (isset($_POST['id_pelanggan'])) {
        $query = "UPDATE pelanggan SET nama = ?, no_hp = ? WHERE id_pelanggan = ?";

        $stmt = $mysqli->prepare($query);

        $id_pelanggan = trim($_POST['id_pelanggan']);
        $nama         = trim($_POST['nama']);
        $no_hp        = trim($_POST['no_hp']);

        $stmt->bind_param("ssi", $nama, $no_hp, $id_pelanggan);

        $stmt->execute();

        if ($stmt) {
            echo "sukses";
        } else {
            echo "gagal";
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    echo "<script>window.location='../../index.php'</script>";
}
