<?php 
// Initialize session
session_start();

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== false) {
    header('location: login.php');
    exit;
}
require_once 'config/config.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <!-- favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.png">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/css/dataTables.bootstrap4.min.css">
        <!-- datepicker CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/css/datepicker.min.css">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/fontawesome-free-5.4.1-web/css/all.min.css">
        <!-- Sweetalert CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/css/sweetalert.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <!-- Fungsi untuk membatasi karakter yang diinputkan -->
        <script type="text/javascript" src="assets/js/fungsi_validasi_karakter.js"></script>

        <title>Manufacturing Productivity</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
                <h5 class="my-0 mr-md-auto font-weight-normal"><i class="fas fa-chart-bar title-icon"></i> Manufacturing Productivity</h5>
                <button class="btn btn-secondary" id="refresh">Refresh</button>
                <a class="btn btn-info ml-2" id="btnTambah" href="#" data-toggle="modal" data-target="#modalTambah" role="button"><i class="fas fa-plus"></i> Request</a>
                <a class="btn btn-danger ml-2" href="logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table id="table-data" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Model</th>
                                <th>Quantity</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Achievement</th>
                                <th>OK/NG Ratio</th>
                                <th>FTT</th>
                                <th>Downtime</th>
                                <th>OEE</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal tambah data transaksi penjualan -->
        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambah" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-edit"></i> Input New Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="formTambah">
                        <div class="modal-body">
                          <div class="form-group row">
                            <div class="col-2">
                              <label class="col-form-label">Line:</label>
                            </div>
                            <div class="col-10">
                              <select class="custom-select" id="line" name="line">
                                <option value="">Select Line</option>
                                <?php
                                $queryline = $mysqli->query("SELECT * FROM line");
                                $line = $queryline->fetch_all(MYSQLI_ASSOC);
                                $no = 1;
                                foreach ($line as $row) {
                                ?>
                                <option value="<?= $row['id'] ?>"><?= $row['line'] ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-2">
                              <label class="col-form-label">Model:</label>
                            </div>
                            <div class="col-10">
                              <select class="custom-select" id="model" name="model">
                                <option value="">Select Line First</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-2">
                              <label class="col-form-label">Quantity:</label>
                            </div>
                            <div class="col-10">
                              <input type="number" id="quantity" autocomplete="off" required name="qty" class="form-control" name="qty">
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-2">
                              <label class="col-form-label">Tanggal:</label>
                            </div>
                            <div class="col-10">
                              <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" id="tanggal" name="tanggal" autocomplete="off">
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-2">
                              <label class="col-form-label">Shift:</label>
                            </div>
                            <div class="col-10">
                              <select class="custom-select" id="shift" name="shift">
                                <option value="">Select Shift</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>
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

        <!-- Modal ubah Request -->
        <div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="modalUbah" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-edit"></i> Ubah Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formUbah">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="qty" name="qty" autocomplete="off">
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id_request">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info btn-submit" id="btnUbah">Ubah</button>
                            <button type="button" class="btn btn-secondary btn-reset" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <footer class="pt-4 my-md-4 pt-md-3 border-top">
                <div class="row">
                    <div class="col-12 col-md center">
                        &copy; <?= date('Y') ?> - <a class="text-info" >PT. Astra Visteon Indonesia</a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script type="text/javascript" src="assets/js/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="assets/js/popper.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <!-- fontawesome Plugin JS -->
        <script type="text/javascript" src="assets/plugins/fontawesome-free-5.4.1-web/js/all.min.js"></script>
        <!-- DataTables Plugin JS -->
        <script type="text/javascript" src="assets/plugins/DataTables/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/plugins/DataTables/js/dataTables.bootstrap4.min.js"></script>
        <!-- datepicker Plugin JS -->
        <script type="text/javascript" src="assets/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
        <!-- SweetAlert Plugin JS -->
        <script type="text/javascript" src="assets/plugins/sweetalert/js/sweetalert.min.js"></script>

        <script type="text/javascript">
            
            // fungsi select model ajax
            $(document).ready(function(){
                $('#line').change(function(){
                    var line_id = $(this).val();
                    $.ajax({
                    url: "fetch_model.php",		//Path for PHP file to fetch phone models from DB
                    method: "POST",				//Fetching method
                    data: {line_id:line_id},	//Data send to the server to get the results
                    success:function(data)		//If data fetched successfully from the server, execute this function
                    {
                        // console.log(data);
                        $('#model').html(data);
                    }
                    });
                });
            });

            $(document).ready(function(){
                // initiate plugin ====================================================================================
                // ----------------------------------------------------------------------------------------------------
                // datepicker plugin
                $('.date-picker').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });

                // dataTables plugin
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
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
                // ====================================================================================================

                // Tampil Data ========================================================================================
                // ----------------------------------------------------------------------------------------------------
                // datatables serverside processing
                // var table = $('#table-data').DataTable( {
                //     "bAutoWidth": false,
                //     "scrollY": '58vh',
                //     "scrollCollapse": true,
                //     "processing": true,
                //     "serverSide": true,
                //     "ajax":{
                //         "url": "ajax_data.php?action=table_data",
                //         "dataType": "json",
                //         "type": "POST"
                //     },
                //     "columns": [
                //         { "data": "no" , "orderable": false, "searchable": false, "width": '30px', "className": 'center'},
                //         { "data": "model", "width": '100px', "className": 'center'  },
                //         { "data": "qty", "width": '50px', "className": 'center'  },
                //         { "data": "date", "width": '80px', "className": 'center'  },
                //         { "data": "shift", "width": '50px', "className": 'center'  },
                //         {
                //             "targets": 5, "data": "id", "orderable": false, "searchable": false, "width": '20px', "className": 'center',
                //             "render": function(data, type, row) {
                //                 var btn = "<a style=\"margin-right:7px\" title=\"Ubah\" class=\"btn btn-info btn-sm getUbah\" href=\"#\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"#\"><i class=\"fas fa-trash\"></i></a>";
                //                 return btn;
                //             } 
                //         }
                //     ],
                //     "order": [[ 1, "desc" ]],           // urutkan data berdasarkan id_transaksi secara descending
                //     "iDisplayLength": 10,               // tampilkan 10 data
                //     "rowCallback": function (row, data, iDisplayIndex) {
                //         var info   = this.fnPagingInfo();
                //         var page   = info.iPage;
                //         var length = info.iLength;
                //         var index  = page * length + (iDisplayIndex + 1);
                //         $('td:eq(0)', row).html(index);
                //     }
                // } );
                var table = $('#table-data').DataTable( {
                    "bAutoWidth": false,
                    "scrollY": '58vh',
                    "scrollCollapse": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": 'data_transaksi.php',     // panggil file data_transaksi.php untuk menampilkan data transaksi dari database
                    "columnDefs": [ 
                        { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                        { "targets": 1, "width": '130px', "className": 'center' },
                        { "targets": 2, "width": '80px', "className": 'center' },
                        { "targets": 3, "width": '170px', "className": 'center' },
                        { "targets": 4, "width": '50px', "className": 'center' },
                        { "targets": 5, "width": '50px', "className": 'center' },
                        { "targets": 6, "width": '100px', "className": 'center' },
                        { "targets": 7, "width": '50px', "className": 'center' },
                        { "targets": 8, "width": '50px', "className": 'center' },
                        { "targets": 9, "width": '50px', "className": 'center' },
                        {
                        "targets": 10, "data": null, "orderable": false, "searchable": false, "width": '120px', "className": 'center',
                        "render": function(data, type, row) {
                            var btn = "<a style=\"margin-right:7px\" title=\"Ubah\" class=\"btn btn-info btn-sm getUbah\" href=\"#\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"#\"><i class=\"fas fa-trash\"></i></a>";
                            return btn;
                        } 
                        } 
                    ],
                    "order": [[ 1, "desc" ]],           // urutkan data berdasarkan id_transaksi secara descending
                    "iDisplayLength": 10,               // tampilkan 10 data
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info   = this.fnPagingInfo();
                        var page   = info.iPage;
                        var length = info.iLength;
                        var index  = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                } );
                // ====================================================================================================

                // Simpan Data ========================================================================================
                // ----------------------------------------------------------------------------------------------------
                // Tampikan Form Tambah Data
                $('#btnTambah').click(function(reload){
                    // reset form
                    $('#formTambah')[0].reset();
                });

                // Proses Simpan Data
                $('#btnSimpan').click(function(){
                    // Validasi form input
                    // jika tanggal kosong
                    if ($('#line').val()==""){
                        // focus ke input line
                        $( "#line" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Line tidak boleh kosong.", "warning");
                    }
                    // jika model kosong
                    else if ($('#model').val()==""){
                        // focus ke input model
                        $( "#model" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Model tidak boleh kosong.", "warning");
                    }
                    // jika quantity kosong atau 0 (nol)
                    else if ($('#quantity').val()=="" || $('#quantity').val()==0){
                        // focus ke input quantity
                        $( "#quantity" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Jumlah quantity tidak boleh kosong atau 0 (nol).", "warning");
                    }
                    // jika tanggal kosong atau 0 (nol)
                    else if ($('#tanggal').val()=="" || $('#tanggal').val()==0){
                        // focus ke input tanggal
                        $( "#tanggal" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Jumlah Tanggal tidak boleh kosong atau 0 (nol).", "warning");
                    }
                    // jika shift kosong
                    else if ($('#shift').val()==""){
                        // focus ke input shift
                        $( "#shift" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Shift tidak boleh kosong.", "warning");
                    }
                    // jika semua data sudah terisi, jalankan perintah simpan data
                    else{
                        var data = $('#formTambah').serialize();
                        $.ajax({
                            type : "POST",
                            url  : "proses_simpan.php",
                            data : data,
                            success: function(result){                          // ketika sukses menyimpan data
                                if (result==="sukses") {
                                    // tutup modal tambah data transaksi
                                    $('#modalTambah').modal('hide');
                                    // tampilkan pesan sukses simpan data
                                    swal("Sukses!", "Request berhasil disimpan.", "success");
                                    // tampilkan data transaksi
                                    var table = $('#table-data').DataTable(); 
                                    table.ajax.reload( null, false );
                                } else {
                                    // tampilkan pesan gagal simpan data
                                    swal("Gagal!", "Request tidak bisa disimpan.", "error");
                                }
                            }
                        });
                        return false;
                    }
                });

                // Tampilkan Form Ubah Data
                $('#table-data tbody').on( 'click', '.getUbah', function (){
                    var data = table.row( $(this).parents('tr') ).data();
                    var id = data[ 10 ];
                    
                    $.ajax({
                        type : "GET",
                        url  : "get_transaksi.php",
                        data : {id:id},
                        dataType : "JSON",
                        success: function(result){
                            // tampilkan modal ubah data transaksi
                            $('#modalUbah').modal('show');
                            // tampilkan data transaksi
                            $('#id_request').val(result.id);
                            $('#qty').val(result.qty);
                        }
                    });
                });

                // Proses Ubah Data
                $('#btnUbah').click(function(){
                    // Validasi form input
                    // jika qty kosong
                    if ($('#qty').val()==""){
                        // focus ke input qty
                        $( "#qty" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Quantity tidak boleh kosong.", "warning");
                    }
                    // jika semua data sudah terisi, jalankan perintah ubah data
                    else{
                        var data = $('#formUbah').serialize();
                        $.ajax({
                            type : "POST",
                            url  : "proses_ubah.php",
                            data : data,
                            success: function(result){ // ketika sukses mengubah data
                                if (result==="sukses") {
                                    // tutup modal ubah data transaksi
                                    $('#modalUbah').modal('hide');
                                    // tampilkan pesan sukses ubah data
                                    swal("Sukses!", "Request berhasil diubah.", "success");
                                    // tampilkan data transaksi
                                    var table = $('#table-data').DataTable(); 
                                    table.ajax.reload( null, false );
                                } else {
                                    // tampilkan pesan gagal ubah data
                                    swal("Gagal!", "Request tidak bisa diubah.", "error");
                                }
                            }
                        });
                        return false;
                    }
                });
                // ====================================================================================================
                
                // Proses Hapus Data ==================================================================================
                // ----------------------------------------------------------------------------------------------------
                $('#table-data tbody').on( 'click', '.btnHapus', function (){
                    var data = table.row( $(this).parents('tr') ).data();
                    // tampilkan notifikasi saat akan menghapus data
                    swal({
                        title: "Apakah Anda Yakin?",
                        text: "Anda akan menghapus data",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Hapus!",
                        closeOnConfirm: false
                    }, 
                    // jika dipilih ya, maka jalankan perintah hapus data
                    function () {
                        var id = data[ 10 ];
                        $.ajax({
                            type : "POST",
                            url  : "proses_hapus.php",
                            data : {id:id},
                            success: function(result){ // ketika sukses menghapus data
                                if (result==="sukses") {
                                    // tampilkan pesan sukses hapus data
                                    swal("Sukses!", "Request berhasil dihapus.", "success");
                                    // tampilkan data transaksi
                                    var table = $('#table-data').DataTable(); 
                                    table.ajax.reload( null, false );
                                } else {
                                    // tampilkan pesan gagal hapus hapus data
                                    swal("Gagal!", "Request tidak bisa dihapus.", "error");
                                }
                            }
                        });
                    });
                });
            });
        </script>
        <script>
            $('#refresh').click(function() {
              location.reload();
            });
        </script>
    </body>
</html>