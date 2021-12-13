<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {

    // nama table
    $table = 'log';
    // Table's primary key
    $primaryKey = 'id';
    $columns = array(
        array( 'db' => 'serial_number', 'dt' => 1 ),
        array( 'db' => 'judgement', 'dt' => 2 ),
        array( 'db' => 'line', 'dt' => 3 ),
        array( 'db' => 'model', 'dt' => 4 ),
        array( 'db' => 'shift', 'dt' => 5 ),
        array( 'db' => 'station', 'dt' => 6 ),
        array( 'db' => 'created_at', 'dt' => 7 ),
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
    echo '<script>window.location="log.php"</script>';
}
?>