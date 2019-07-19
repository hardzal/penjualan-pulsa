<div class="content-header row mb-3">
    <div class="col-md-12">
        <h5>
            <i class="fas fa-user title-icon"></i> Data Pelanggan
            <a class="btn btn-info float-right" id="btnTambah" href="javascript: void(0);" data-toggle="modal" data-target="#modalPelanggan" role="button"><i class="fas fa-plus"></i> Tambah</a>
        </h5>
    </div>
</div>

<div class="border mb-4"></div>

<div class="row">
    <div class="col-md-12">
        <table id="tabel-pelanggan" class="table table-striped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>No. Hp</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalPelanggan" tabindex="-1" role="dialog" aria-labelledby="modalPelanggan" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit title-icon"></i><span id="modalLabel"></span></h5>
                <button type="submit" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formPelanggan">
                <div class="modal-body">
                    <input type="hidden" id="id_pelanggan" name="id_pelanggan" />

                    <div class="form-group">
                        <label for="nama">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label>No Hp</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="13" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-submit" id="btnSimpan">Simpan</button>
                    <button type="button" class="btn btn-secodary btn-reset" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript'>
    $(document).ready(function() {
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var table = $('#tabel-pelanggan').DataTable({
            "scrollv": '45vh',
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "ajax": 'modules/pelanggan/data.php',
            "columnDefs": [{
                    "targets": 0,
                    "data": null,
                    "orderable": false,
                    "width": '30px',
                    "className": 'center'
                },
                {
                    "targets": 1,
                    "visible": false
                },
                {
                    "targets": 2,
                    "width": '100px'
                },
                {
                    "targets": 3,
                    "width": '100px',
                    "className": 'center'
                },
                {
                    "targets": 4,
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "width": '70px',
                    "className": 'center',
                    "render": function(data, type, row) {
                        var btn = "<a style=\"margin-right: 7px\" title=\"Ubah\" class=\"btn btn-info btn-sm getUbah\" href=\"javascript:void(0);\"><i class=\"fas fa-edit\"></i><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0);\"><i class=\"fas fa-trash\"></i></a>";
                        return btn;
                    }
                }
            ],
            "order": [
                [1, "desc"]
            ],
            "iDisplayLength": 10,
            "rowCallback": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });


        $('#btnTambah').click(function() {
            $('#formPelanggan')[0].reset();
            $('#modalLabel').text('Entri Data Pelanggan');
        });


        $('#tabel-pelanggan tbody').on('click', '.getUbah', function() {
            $('#modalLabel').text('Ubah Data Pelanggan');
            console.log("Hello!");
            var data = table.row($(this).parents('tr')).data();

            var id_pelanggan = data[1];
            console.log(id_pelanggan);
            $.ajax({
                type: "GET",
                url: "modules/pelanggan/get_data.php",
                data: {
                    id_pelanggan: id_pelanggan
                },
                dataType: "json",
                success: function(result) {
                    console.log(data);
                    $('#modalPelanggan').modal('show');
                    $('#id_pelanggan').val(result.id_pelanggan);
                    $('#nama').val(result.nama);
                    $('#no_hp').val(result.no_hp);
                }
            });
        });

        $('#btnSimpan').click(function() {
            if ($('#nama').val() == "") {
                $('#nama').focus();
                swal("Peringatan", "Nama pelanggan tidak boleh kosong.", "warning");
            } else if ($('#no_hp').val() == "") {
                $('#no_hp').focus();
                swal("Peringatan!", "No. Hp tidak boleh kosong.", "warning");
            } else {
                if ($('#modalLabel').text() == "Entri Data Pelanggan") {
                    var data = $('#formPelanggan').serialize();

                    $.ajax({
                        type: "POST",
                        url: "modules/pelanggan/insert.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPelanggan')[0].reset();
                                $('#modalPelanggan').modal('hide');
                                swal("Sukses!", "Data Pelanggan berhasil disimpan.", "success");
                                var table = $('#tabel-pelanggan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Pelanggan tidak bisa disimpan.", "error");
                            }
                        }
                    });
                    return false;
                } else if ($('#modalLabel').text() == "Ubah Data Pelanggan") {
                    var data = $('#formPelanggan').serialize();

                    $.ajax({
                        type: "POST",
                        url: "modules/pelanggan/update.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPelanggan')[0].reset();
                                $('#modalPelanggan').modal('hide');
                                swal("Sukses!", "Data Pelanggan berhasil diubah", "success");
                                var table = $('#tabel-pelanggan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Pelanggan tidak bisa diubah", "error");
                            }
                        }
                    });
                    return false;
                }
            }
        });

        $('#tabel-pelanggan tbody').on('click', '.btnHapus', function() {
            var data = table.row($(this).parents('tr')).data();

            swal({
                    title: "Apakah anda yakin?",
                    text: "Anda akan menghapus data Pelanggan : " + data[2] + "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6855",
                    confirmButtonText: "Ya, Hapus!",
                    closeOnConfirm: false
                },
                function() {
                    var id_pelanggan = data[1];

                    $.ajax({
                        type: "POST",
                        url: "modules/pelanggan/delete.php",
                        data: {
                            id_pelanggan: id_pelanggan
                        },
                        success: function(result) {
                            if (result == "sukses") {
                                swal("Sukses!", "Data Pelanggan berhasil dihapus.", "success");
                                var table = $('#tabel-pelanggan').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Pelanggan tidak bisa dihapus", "error");
                            }
                        }
                    });
                });
        });
    });
</script>