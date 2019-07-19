<?php

require_once '../../config/config.php';

if (isset($_GET['tgl_awal'])) :
    header("Content-Type: application/force-download");
    header("Cache-Control: no-cache, must-revalidate");

    header("content-disposition: attachment; filename=LAPORAN-DATA-PENJUALAN-TANGGAL-" . $_GET['tgl_awal'] . "-s/d-" . $_GET['tgl_akhir'] . ".xls");

    ?>

    <div class='center'>
        <h3>LAPORAN DATA PENJUALAN</h3>
    </div>

    <table border='1'>
        <h3>
            <thead>
                <tr class="center" valign="middle">No.</tr>
                <tr class="center" valign="middle">Tanggal</tr>
                <tr class="center" valign="middle">Nama Pelanggan</tr>
                <tr class="center" valign="middle">No. Hp</tr>
                <tr class="center" valign="middle">Pulsa</tr>
                <tr class="center" valign="middle">Jumlah Bayar</tr>
            </thead>
        </h3>

        <tbody>
            <?php
            $no = 1;

            $total = 0;

            $query = "SELECT a.id_penjualan, a.tanggal, a.pelanggan, a.pulsa, a.jumlah_bayar, b.nama, b.no_hp, c.provider, c.nominal FROM penjualan AS a INNER JOIN pelanggan AS b INNER JOIN pulsa AS c ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa WHERE a.tanggal BETWEEN ? and ? ORDER BY a.id_penjualan ASC";

            $stmt = $mysqli->prepare($query);

            if (!$stmt) {
                die("Query Error: " . $mysqli->errno . "-" . $mysqli->error);
            }

            $tgl_awal = date("Y-m-d", strtotime($_GET['tgl_awal']));
            $tgl_akhir = date("Y-m-d", strtotime($_GET['tgl_akhir']));

            $stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()) {
                echo "<tr>
                            <td width='30' class='center'>" . $no . "</td>
                            <td width='90' class='center'>" . date('d-m-Y', strtotime($data['tanggal'])) . "</td>
                            <td width='170'>" . $data['nama'] . "</td>
                            <td width='90' class='center'>" . $data['no_hp'] . "</td>
                            <td width='170'>" . $data['provider'] . " - " . number_format($data['nominal']) . "</td>
                            <td width='100' class='right'>Rp." . number_format($data['jumlah_bayar']) . "</td>
                    </tr>";
                $no++;
                $total += $data['jumlah_bayar'];
            }

            echo "<tr>
                <td class='center' colspan='5'><strong>Total</strong></td>
                <td class='right'><strong>Rp. " . number_format($total) . "</strong></td>
            </tr>";

            $stmt->close();
            ?>
        </tbody>
    </table>
<?php endif;
$mysqli->close();
?>