<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {

    // nama table
    $table = <<<EOT
    (
        SELECT request.id as id, qty, model_id, date, shift, model.model as model 
        FROM request
        JOIN model 
        WHERE request.model_id = model.id
    ) temp
    EOT;
    // Table's primary key
    $primaryKey = 'id';
    $columns = array(
        array( 'db' => 'model', 'dt' => 1 ),
        array( 'db' => 'qty', 'dt' => 2 ),
        array( 'db' => 'date', 'dt' => 3 ),
        array( 'db' => 'shift', 'dt' => 4 ),
        array( 
            'db' => 'qty', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {
                $model = $row['model'];
                $qty = $row['qty'];
                $date = $row['date'];
                $shift = $row['shift'];
                // parameter koneksi database
                $sql_details = array( 'user' => 'root', 'pass' => '', 'db'   => 'oee', 'host' => 'localhost' );
                $con = $sql_details;
                // koneksi database
                $mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);
                // QUERY UNIQUE OK
                $queryok = $mysqli->query("SELECT count(DISTINCT(serial_number)) from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND judgement = 'OK' AND shift = '$shift'");
                $ok = $queryok->fetch_row()[0];
                if($ok) {
                $achv = round($ok/$qty * 100,2);
                  return $achv."%";
                } else {
                  return "-";
                }
            } ),
        array( 
            'db' => 'qty', 
            'dt' => 6,
            'formatter' => function( $d, $row ) {
                $model = $row['model'];
                $qty = $row['qty'];
                $date = $row['date'];
                $shift = $row['shift'];
                // parameter koneksi database
                $sql_details = array( 'user' => 'root', 'pass' => '', 'db'   => 'oee', 'host' => 'localhost' );
                $con = $sql_details;
                // koneksi database
                $mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);
                // QUERY UNIQUE OK
                $queryok = $mysqli->query("SELECT count(DISTINCT(serial_number)) from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND judgement = 'OK' AND shift = '$shift'");
                $ok = $queryok->fetch_row()[0];
                $querytot = $mysqli->query("SELECT count(DISTINCT(serial_number)) as jumlah from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND shift = '$shift'");
                $total = $querytot->fetch_row()[0];
                if($total && $ok){
                  $okng = round($ok/$total * 100,2);
                  return $okng."%";
                } else {
                  return "-";
                }
            } ),
        array( 
            'db' => 'qty', 
            'dt' => 7,
            'formatter' => function( $d, $row ) {
                $model = $row['model'];
                $qty = $row['qty'];
                $date = $row['date'];
                $shift = $row['shift'];
                // parameter koneksi database
                $sql_details = array( 'user' => 'root', 'pass' => '', 'db'   => 'oee', 'host' => 'localhost' );
                $con = $sql_details;
                // koneksi database
                $mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);
                // QUERY OK
                $queryok = $mysqli->query("SELECT count(serial_number) from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND judgement = 'OK' AND shift = '$shift'");
                $ok_nu = $queryok->fetch_row()[0];
                // QUERY TOTAL
                $querytot = $mysqli->query("SELECT count(serial_number) as jumlah from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND shift = '$shift'");
                $total_nu = $querytot->fetch_row()[0];
                // Kolom FTT
                if($total_nu && $ok_nu)
                return $ok_nu ."/". $total_nu;
                else
                return "-";
            } ),
        array( 
            'db' => 'qty', 
            'dt' => 8,
            'formatter' => function( $d, $row ) {
                $model = $row['model'];
                $date = $row['date'];
                $shift = $row['shift'];
                // parameter koneksi database
                $sql_details = array( 'user' => 'root', 'pass' => '', 'db'   => 'oee', 'host' => 'localhost' );
                $con = $sql_details;
                // koneksi database
                $mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);
                // QUERY INTERVAL
                $queryintrvl = $mysqli->query("SELECT sum(intrvl) as sumintrvl from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND shift = '$shift'");
                $intrvl = $queryintrvl->fetch_row()[0];
                if($intrvl)
                return gmdate("H:i:s", $intrvl);
                else
                return "-";
            } ),
        array( 
            'db' => 'qty', 
            'dt' => 9,
            'formatter' => function( $d, $row ) {
                $model = $row['model'];
                $date = $row['date'];
                $shift = $row['shift'];
                // parameter koneksi database
                $sql_details = array( 'user' => 'root', 'pass' => '', 'db'   => 'oee', 'host' => 'localhost' );
                $con = $sql_details;
                // koneksi database
                $mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);
                // QUERY OK
                $queryok = $mysqli->query("SELECT count(serial_number) from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND judgement = 'OK' AND shift = '$shift'");
                $ok_nu = $queryok->fetch_row()[0];
                // QUERY TOTAL
                $querytot = $mysqli->query("SELECT count(serial_number) as jumlah from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND shift = '$shift'");
                $total_nu = $querytot->fetch_row()[0];
                // QUERY INTERVAL
                $queryintrvl = $mysqli->query("SELECT sum(intrvl) as sumintrvl from log WHERE model = '$model' AND LEFT(created_at,10) = '$date' AND shift = '$shift'");
                $intrvl = $queryintrvl->fetch_row()[0];
                if($intrvl)
                return gmdate("H:i:s", $intrvl);
                else
                return "-";
            } ),
        array( 'db' => 'id', 'dt' => 10 ),
    );

    // SQL server connection information
    require_once "config/database.php";
    // ssp class
    require 'config/ssp.class.php';
    // require 'config/ssp.class.php';

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
} else {
    echo '<script>window.location="index.php"</script>';
}
?>