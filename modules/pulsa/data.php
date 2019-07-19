<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    $table = 'pulsa';

    $primaryKey = 'id_pulsa';

    $columns = array(
        array('db' => 'provider', 'dt' => 1),
        array(
            'db'        => 'nominal',
            'dt'        => 2,
            'formatter' => function ($d, $row) {
                return number_format($d);
            }
        ),
        array(
            'db'        => 'harga',
            'dt'        => 3,
            'formatter' => function ($d, $row) {
                return 'Rp ' . number_format($d);
            }
        ),
        array('db' => 'id_pulsa', 'dt' => 4)
    );

    require_once "../../config/database.php";

    require '../../config/ssp.class.php';

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
    );
} else {
    echo "<script>window.location ='../../index.php'</script>";
}
