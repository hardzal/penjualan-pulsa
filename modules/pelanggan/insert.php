<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';
    $query = "INSERT INTO pelanggan(nama, no_hp) VALUES(?, ?)";

    $stmt = $mysqli->prepare($query);

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);

    $stmt->bind_param("ss", $nama, $no_hp);

    $stmt->execute();
    if ($stmt) {
        echo "sukses";
    } else {
        echo "gagal";
    }
    $stmt->close();
    $mysqli->close();
} else {
    echo '<script>window.location="../../index.php"</script>';
}
