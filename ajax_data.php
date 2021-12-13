<?php 
require_once 'config/config.php';

if($_GET['action'] == "table_data"){
		$columns = array( 
            0 =>'id', 
            1 =>'model_id', 
            2 =>'qty',
            3=> 'date',
            4=> 'shift',
        );
		$querycount = $mysqli->query("SELECT count(id) as jumlah FROM request");
		$datacount = $querycount->fetch_array();  
        $totalData = $datacount['jumlah'];  
        $totalFiltered = $totalData; 

        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
        
        if(empty($_POST['search']['value']))
        {            
        	$query = $mysqli->query("SELECT request.id as id, qty, model_id, date, shift, model.model as model FROM request JOIN model WHERE request.model_id = model.id order by request.date DESC LIMIT $limit OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $mysqli->query("SELECT request.id as id, qty, model_id, date, shift, model.model as model FROM request JOIN model WHERE request.model_id = model.id WHERE model.model LIKE '%$search%' or date LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

            $querycount = $mysqli->query("SELECT count(fct.id) as jumlah FROM request JOIN model WHERE request.model_id = model.id WHERE model.model LIKE '%$search%' or date LIKE '%$search%'");
            $datacount = $querycount->fetch_array();
            $totalFiltered = $datacount['jumlah'];
        }

        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
            while ($r = $query->fetch_array())
            {
                $nestedData['no'] = $no;
                $nestedData['model'] = $r['model'];
                $nestedData['qty'] = $r['qty'];
                $nestedData['date'] = $r['date'];
                $nestedData['shift'] = $r['shift'];
                $nestedData['id'] = $r['id'];
                $data[] = $nestedData;
                $no++;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 

}