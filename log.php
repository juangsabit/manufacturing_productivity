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

        <title>Log Manufacturing</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
                <h5 class="my-0 mr-md-auto font-weight-normal"><i class="fas fa-chart-bar title-icon"></i> Log Manufacturing</h5>
                <button class="btn btn-secondary" id="refresh">Refresh</button>
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
                                <th>Serial Number</th>
                                <th>Judgement</th>
                                <th>Line</th>
                                <th>Model</th>
                                <th>Shift</th>
                                <th>Station</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                    </table>
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
                var table = $('#table-data').DataTable( {
                    "bAutoWidth": false,
                    "scrollY": '58vh',
                    "scrollCollapse": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": 'data_log.php',     // panggil file data_transaksi.php untuk menampilkan data transaksi dari database
                    "columnDefs": [ 
                        { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                        { "targets": 1, "width": '130px', "className": 'center' },
                        { "targets": 2, "width": '80px', "className": 'center' },
                        { "targets": 3, "width": '50px', "className": 'center' },
                        { "targets": 4, "width": '50px', "className": 'center' },
                        { "targets": 5, "width": '50px', "className": 'center' },
                        { "targets": 6, "width": '50px', "className": 'center' },
                        { "targets": 7, "width": '100px', "className": 'center' },
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
            });
        </script>
        <script>
            $('#refresh').click(function() {
              location.reload();
            });
        </script>
    </body>
</html>