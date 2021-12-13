<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file config.php untuk koneksi ke database
	require_once "config/config.php";

	$model = $_POST['model'];
	$qty = $_POST['qty'];
	$tanggal = date("Y-m-d", strtotime($_POST['tanggal']));;
	$shift = $_POST['shift'];

	// perintah query untuk menyimpan data ke tabel request
	$insert = $mysqli->query("INSERT INTO request(model_id,qty,date,shift)
	                          VALUES('$model','$qty','$tanggal','$shift')")
	                          or die('Ada kesalahan pada query insert : '.$mysqli->error); 
	// cek query
	if ($insert) {
	    // jika berhasil tampilkan pesan berhasil simpan data
	    echo "sukses";
	} else {
		// jika gagal tampilkan pesan gagal simpan data
	    echo "gagal";
	}
	// tutup koneksi
	$mysqli->close();   
} else {
    echo '<script>window.location="index.php"</script>';
}
?>