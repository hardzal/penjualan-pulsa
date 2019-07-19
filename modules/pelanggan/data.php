<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest') {
    $table = 'pelanggan';

    $primaryKey  = 'id_pelanggan';

    $columns = array(
        array('db' => 'id_pelanggan', 'dt' => 1),
        array('db' => 'nama', 'dt' => 2),
        array('db' => 'no_hp', 'dt' => 3),
        array('db' => 'id_pelanggan', 'dt' => 4)
    );

    require_once '../../config/database.php';
    require '../../config/ssp.class.php';

    echo json_encode(
        SSP::simple(
            $_GET,
            $sql_details,
            $table,
            $primaryKey,
            $columns
        )
    );
} else {
    echo '<script>window.location="../../index.php"</script>';
}
