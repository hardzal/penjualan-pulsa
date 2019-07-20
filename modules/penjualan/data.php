<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    $table = <<<EOT
(
    SELECT a.id_penjualan, a.tanggal, a.pelanggan, a.pulsa, a.jumlah_bayar, b.nama, b.no_hp, c.provider, c.nominal FROM penjualan AS a INNER JOIN pelanggan AS b INNER JOIN pulsa AS c ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa
) temp
EOT;

    $primaryKey = 'id_penjualan';

    $columns = array(
        array('db' => 'id_penjualan', 'dt' => 1),
        array(
            'db' => 'tanggal',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                return date('d-m-Y', strtotime($d));
            }
        ),
        array('db' => 'pelanggan', 'dt' => 3),
        array('db' => 'nama', 'dt' => 4),
        array('db' => 'no_hp', 'dt' => 5),
        array('db' => 'pulsa', 'dt' => 6),
        array('db' => 'provider', 'dt' => 7),
        array(
            'db'        => 'nominal',
            'dt'        => 8,
            'formatter' => function ($d, $row) {
                return number_format($d);
            }
        ),
        array(
            'db'        => 'jumlah_bayar',
            'dt'        => 9,
            'formatter' => function ($d, $row) {
                return number_format($d);
            }
        ),
        array('db' => 'id_penjualan', 'dt' => 10)
    );

    require_once '../../config/database.php';
    require '../../config/ssp.class.php';

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
    );
} else {
    echo "<script>window.location='../../index.php'</script>";
}
