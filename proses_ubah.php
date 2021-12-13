<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file config.php untuk koneksi ke database
    require_once "config/config.php";

    if (isset($_POST['id'])) {
        // ambil data hasil post dari ajax
        $id = $_POST['id'];
        $qty = $_POST['qty'];

        // perintah query untuk mengubah data pada tabel transaksi
        $update = $mysqli->query("UPDATE request SET qty = '$qty' WHERE id = '$id'")
                                  or die('Ada kesalahan pada query update : '.$mysqli->error);
        // cek query
        if ($update) {
            // jika berhasil tampilkan pesan berhasil ubah data
            echo "sukses";
        } else {
            // jika gagal tampilkan pesan gagal ubah data
            echo "gagal";
        }
    }
    // tutup koneksi
    $mysqli->close();   
} else {
    echo '<script>window.location="index.php"</script>';
}
?>