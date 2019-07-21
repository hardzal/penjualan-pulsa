<div class="content-header row mb-3">
    <div class="col-md-12">
        <h5>
            <i class="fas fa-shopping-cart title-icon"></i> Data Penjualan
            <a class="btn btn-info float-right" id="btnTambah" href="javascript: void(0);" data-toggle="modal" data-target="#modalPenjualan" role="button"><i class="fas fa-plus"></i> Tambah</a>
        </h5>
    </div>
</div>

<div class="border mb-4"></div>

<div class="row">
    <div class="col-md-12">
        <table id="tabel-penjualan" class="table table-striped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Penjualan</th>
                    <th>Tanggal</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>No Hp</th>
                    <th>ID Pulsa</th>
                    <th>Pulsa</th>
                    <th>Nominal</th>
                    <th>Jumlah Bayar</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalPenjualan" tabindex="-1" role="dialog" aria-labelledby="modalPenjualan" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit title-icon"></i><span id="modalLabel"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPenjualan">
                <div class="modal-body">
                    <input type="hidden" id="id_penjualan" name="id_penjualan" />

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" id="tanggal" name="tanggal" value="<?= date('d-m-Y'); ?>" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label>No. HP</label>
                        <select class="chosen-select" id="id_pelanggan" name="id_pelanggan" onchange="get_pelanggan();" autocomplete="off">
                            <option value="0">--Pilih--</option>
                            <?php
                            require_once '../../config/config.php';

                            $query = "SELECT id_pelanggan, no_hp FROM pelanggan ORDER BY no_hp ASC";

                            $stmt = $mysqli->prepare($query);

                            if (!$stmt) {
                                die("Query Error:   " . $mysqli->errno . " -   " . $mysqli->error);
                            }

                            $stmt->execute();

                            $result = $stmt->get_result();

                            while ($data_pelanggan = $result->fetch_assoc()) {
                                ?>
                                <option value="<?= $data_pelanggan['id_pelanggan']; ?>"><?= $data_pelanggan['no_hp']; ?></option>
                            <?php }

                            $stmt->close();
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" id="nama" readonly />
                    </div>

                    <div class="form-group">
                        <label for="id_pulsa">Pulsa</label>
                        <select name="id_pulsa" id="id_pulsa" class="chosen-select" autocomplete="off" onchange="get_pulsa()">
                            <option value="#">--Pilih--</option>
                            <?php
                            $query = "SELECT id_pulsa, provider, nominal FROM pulsa ORDER BY provider ASC, nominal ASC";

                            $stmt = $mysqli->prepare($query);

                            if (!$stmt) {
                                die("Query Error: " . $mysqli->errno . '-' . $mysqli->error);
                            }

                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($data_pulsa = $result->fetch_assoc()) {
                                ?>
                                <option value="<?= $data_pulsa['id_pulsa']; ?>"><?= $data_pulsa['provider']; ?> - <?= number_format($data_pulsa['nominal']); ?></option>
                            <?php
                            }

                            $stmt->close();

                            $mysqli->close();
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="text" name="harga" class="form-control" id="harga" readonly />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-submit" id="btnSimpan">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secodary btn-reset" data-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript'>
    function get_pelanggan() {
        var id_pelanggan = $('#id_pelanggan').val();

        $.ajax({
            type: "GET",
            url: "modules/penjualan/get_pelanggan.php",
            data: {
                id_pelanggan: id_pelanggan
            },
            dataType: "json",
            success: function(result) {
                $('#nama').val(result.nama);
            }
        });
    }

    function get_pulsa() {
        var id_pulsa = $('#id_pulsa').val();

        $.ajax({
            type: "GET",
            url: "modules/penjualan/get_pulsa.php",
            data: {
                id_pulsa: id_pulsa
            },
            dataType: "json",
            success: function(result) {
                $('#harga').val(result.harga);
            }
        });
    }

    $(document).ready(function() {
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        $('.chosen-select').chosen();

        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsDisplay(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var table = $('#tabel-penjualan').DataTable({
            "scrollY": '45vh',
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "ajax": 'modules/penjualan/data.php',
            "columnDefs": [{
                    "targets": 0,
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "width": '30px',
                    "className": 'center'
                },
                {
                    "targets": 1,
                    "visible": false
                },
                {
                    "targets": 2,
                    "width": '70px',
                    "className": 'center'
                },
                {
                    "targets": 3,
                    "visible": false
                },
                {
                    "targets": 4,
                    "width": '150px'
                },
                {
                    "targets": 5,
                    "width": '100px',
                    "className": 'center'
                },
                {
                    "targets": 6,
                    "visible": false
                },
                {
                    "targets": 7,
                    "width": '170px',
                    "render": function(data, type, row) {
                        return data + " - " + row[8] + "";
                    }
                },
                {
                    "targets": 8,
                    "visible": false
                },
                {
                    "targets": 9,
                    "width": '100px',
                    "className": 'right'
                },
                {
                    "targets": 10,
                    "data": null,
                    "orderable": false,
                    "width": '60px',
                    "className": 'center',
                    "render": function(data, type, row) {
                        var btn = `<a style=\"margin-right: 7px;\" title=\"Ubah\" class=\"btn btn-info btn-sm getUbah\" href=\"javascript:void(0);\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0)\"><i class=\"fas fa-trash\"></i></a>`;
                        return btn;
                    }
                }
            ],
            "order": [
                [1, "desc"]
            ],
            "iDisplayLength": 10,
            "rowCallBack": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });

        $('#btnTambah').click(function() {
            $('#formPenjualan')[0].reset();
            $('#id_pelanggan').val('').trigger('chosen:updated');
            $('#id_pulsa').val('').trigger('chosen:updated');
            $('#modalLabel').text('Entri Data Penjualan');
        });

        $('#tabel-penjualan tbody').on('click', '.getUbah', function() {
            $('#modalLabel').text('Ubah Data Penjualan');

            var data = table.row($(this).parents('tr')).data();

            var id_penjualan = data[1];

            $.ajax({
                type: "GET",
                url: "modules/penjualan/get_data.php",
                data: {
                    id_penjualan: id_penjualan
                },
                dataType: "json",
                success: function(result) {
                    var tgl = result.tanggal;
                    var dateAt = tgl.split('-');
                    var tanggal = dateAt[2] + '-' + dateAt[1] + '-' + dateAt[0];

                    $('#modalPenjualan').modal('show');
                    $('#id_penjualan').val(result.id_penjualan);
                    $('#tanggal').val(tanggal);
                    $('#id_pelanggan').val(result.pelanggan).trigger('chosen:updated');
                    $('#nama').val(result.nama);
                    $('#id_pulsa').val(result.pulsa).trigger('chosen:updated');
                    $('#harga').val(result.jumlah_bayar);
                }
            });
        });

        $('#btnSimpan').click(function() {
            if ($('#tanggal').val() == "") {
                $('#tanggal').focus();
                swal("Peringatan!", "Tanggal tidak boleh kosong", "warning");
            } else if ($('#id_pelanggan').val() == "") {
                $("#id_pelanggan").focus();
                swal("Peringatan!", "Data Pelanggan tidak boleh kosong.", "warning");
            } else if ($('#id_pulsa').val() == "") {
                $("#id_pulsa").focus();
                swal("Peringatan!", "Data Pulsa tidak boleh kosong.", "warning");
            } else {
                if ($('#modalLabel').text() == "Entri Data Penjualan") {
                    var data = $('#formPenjualan').serialize();
                    $.ajax({
                        type: "POST",
                        url: "modules/penjualan/insert.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPenjualan')[0].reset();
                                $('#id_pelanggan').val('').trigger('chosen:updated');
                                $('#id_pulsa').val('').trigger('chosen:updated');
                                $('#modalPenjualan').modal('hide');
                                swal("Sukses!", "Data penjualan berhasil diubah.", "success");
                                var table = $('#tabel-penjualan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data penjualan tidak bisa diubah.", "error");
                            }
                        }
                    });
                    return false;
                } else if ($('#modalLabel').text() == "Ubah Data Penjualan") {
                    var data = $('#formPenjualan').serialize();

                    $.ajax({
                        type: "POST",
                        url: "modules/penjualan/update.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPenjualan')[0].reset();
                                $('#id_pelanggan').val('').trigger('chosen:updated');
                                $('#id_pulsa').val('').trigger('chosen:updated');
                                $('#modalPenjualan').modal('hide');
                                swal("Sukses!", "Data penjualan berhasil diubah", "success");

                                var table = $('#tabel-penjualan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Penjualan tidak bisa diubah", "error");
                            }
                        }
                    });
                    return false;
                }
            }
        });

        $('#tabel-penjualan tbody').on('click', '.btnHapus', function() {
            var data = table.row($(this).parents('tr')).data();

            swal({
                    title: "Apakah Anda Yakin?",
                    text: "Anda akan menghapus data penjualan tanggal : " + data[2] + " - Pelanggan : " + data[4] + " No Hp : " + data[5] + "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6855",
                    confirmButtonText: "Ya, Hapus!",
                    closeOnConfirm: false
                },
                function() {
                    var id_penjualan = data[1];

                    $.ajax({
                        type: "POST",
                        url: "modules/penjualan/delete.php",
                        data: {
                            id_penjualan: id_penjualan
                        },
                        success: function(result) {
                            if (result == "sukses") {
                                swal("Sukses!", "Data penjualan berhasil dihapus.", "success");
                                var table = $('#tabel-penjualan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Penjualan tidak bisa dihapus.", "error");
                            }
                        }
                    });
                });
        });
    });
</script>