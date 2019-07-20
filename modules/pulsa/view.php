<div class="content-header row mb-3">
    <div class="col-md-12">
        <h5>
            <i class="fas fa-tablet-alt title-icon"> Data pulsa</i>
            <a class="btn btn-info float-right" id="btnTambah" href="javascript: void(0);" data-toggle="modal" data-target="#modalPulsa" role="button"><i class="fas fa-plus"></i> Tambah</a>
        </h5>
    </div>
</div>

<div class="border mb-4"></div>

<div class="row">
    <div class="col-md-12">
        <table id="tabel-pulsa" class="table table-striped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Provider</th>
                    <th>Nominal</th>
                    <th>Harga</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalPulsa" tabindex="-1" role="dialog" aria-labelledby="modalPulsa" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit title-icon"></i><span id="modalLabel"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formPulsa">
                <div class="modal-body">
                    <input type="hidden" id="id_pulsa" name="id_pulsa" />

                    <div class="form-group">
                        <label for="provider">Provider</label>
                        <input type="text" name="provider" class="form-control" id="provider" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" name="nominal" class="form-control" id="nominal" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="text" name="harga" id="harga" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-submit" id="btnSimpan">Simpan</button>
                    <button type="button" class="btn btn-secondary btn-reset" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var table = $('#tabel-pulsa').DataTable({
            "scrollY": "45vh",
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "ajax": "modules/pulsa/data.php",
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
                    "width": '200px'
                },
                {
                    "targets": 2,
                    "width": '80px',
                    "className": 'right'
                },
                {
                    "targets": 3,
                    "width": '80px',
                    "className": 'right'
                },
                {
                    "targets": 4,
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "width": '70px',
                    "className": 'center',
                    "render": function(data, type, row) {
                        var btn = `<a style=\"margin-right: 7px;\" title=\"Ubah\" class=\"btn btn-info btn-sm getUbah\" href=\"javascript: void(0);\"><i class=\"fas fa-edit\"></i></a> <a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript: void(0);\"><i class=\"fas fa-trash\"></i></a>`;
                        return btn;
                    }
                }
            ],
            "order": [
                [1, "asc"]
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
            $('#formPulsa')[0].reset();
            $('#modalLabel').text("Entri Data Pulsa");
        });

        $('#tabel-pulsa tbody').on('click', '.getUbah', function() {
            $('#modalLabel').text("Ubah Data Pulsa");

            var data = table.row($(this).parents('tr')).data();

            var id_pulsa = data[4];
            console.log(data);
            $.ajax({
                type: "GET",
                url: "modules/pulsa/get_data.php",
                data: {
                    id_pulsa: id_pulsa
                },
                dataType: "json",
                success: function(result) {
                    console.log(result);
                    $('#modalPulsa').modal('show');
                    $('#id_pulsa').val(result.id_pulsa);
                    $('#provider').val(result.provider);
                    $('#nominal').val(result.nominal);
                    $('#harga').val(result.harga);
                }
            });
        });

        $('#btnSimpan').click(function() {
            if ($('#provider').val() == '') {
                $('#provider').focus();
                swal("Peringatan!", "Provider tidak boleh kosong.", "warning");
            } else if ($('#nominal').val() == '' || $('#nominal').val() == 0) {
                $('#harga').focus();
                swal("Peringatan!", "Nominal tidak boleh kosong atau 0 (nol).", "warning");
            } else if ($('#harga').val() == '' || $('#harga').val() == 0) {
                $('#harga').focus();
                swal("Peringatan!", "Harga tidak boleh kosong atau 0 (nol).", "warning");
            } else {
                if ($('#modalLabel').text() == "Entri Data Pulsa") {
                    var data = $('#formPulsa').serialize();

                    $.ajax({
                        type: "POST",
                        url: "modules/pulsa/insert.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPulsa')[0].reset();
                                $('#modalPulsa').modal('hide');
                                swal("Sukses!", "Data Pulsa berhasil disimpan", "success");
                                var table = $('#tabel-pulsa').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data pulsa tidak bisa disimpan.", "error");
                            }
                        }
                    });
                    return false;
                } else if ($('#modalLabel').text() == "Ubah Data Pulsa") {
                    var data = $('#formPulsa').serialize();

                    $.ajax({
                        type: "POST",
                        url: "modules/pulsa/update.php",
                        data: data,
                        success: function(result) {
                            if (result == "sukses") {
                                $('#formPulsa')[0].reset();
                                $('#modalPulsa').modal('hide');
                                swal("Sukses!", "Data Pulsa berhasil diubah.", "success");
                                var table = $('#tabel-pulsa').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Pulsa tidak bisa diubah.", "error");
                            }
                        }
                    });
                    return false;
                }
            }
        });

        $('#tabel-pulsa tbody').on('click', '.btnHapus', function() {
            var data = table.row($(this).parents('tr')).data();

            swal({
                    title: "Apakah Anda Yakin?",
                    text: "Anda akan menghapus data Provider : " + data[1] + " - Nominam : " + data[2] + "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Hapus!",
                    closeOnConfirm: false
                },
                function() {
                    var id_pulsa = data[4];

                    $.ajax({
                        type: "POST",
                        url: "modules/pulsa/delete.php",
                        data: {
                            id_pulsa: id_pulsa
                        },
                        success: function(result) {
                            if (result == "sukses") {
                                swal("Sukses!", "Data pulsa berhasil dihapus.", "success");
                                var table = $('#tabel-pulsa').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                swal("Gagal!", "Data Pulsa tidak bisa dihapus.", "error");
                            }
                        }
                    });
                });
        });
    });
</script>