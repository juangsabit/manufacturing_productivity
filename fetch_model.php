<?php
    // include database connection file
    require_once "config/config.php";
    if($_POST['line_id'] != ""){
        $output = '';
        $output .= '<option value="" selected>Select Model</option>';
        $querymodel = $mysqli->query("SELECT * FROM model WHERE line_id = '".$_POST["line_id"]."' ");
        $model = $querymodel->fetch_all(MYSQLI_ASSOC);
        foreach ($model as $row) {
            $output .= '<option value="'.$row["id"].'">'.$row["model"].'</option>';
        }
        echo $output;
    } else {
        echo '<option value="" selected>Select Line First</option>';
    }
?>