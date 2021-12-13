# Manufacturing Productivity

Manufacturing Productivity merupakan web aplikasi yang dapat menghitung achievement, ok/ng ratio, ftt, downtime dan oee secara automatis berdasarkan log data yang dikirim oleh mesin di station terakhir didalam suatu proses manufacturing.
	
Aplikasi ini dibangun menggunakan bahasa pemrograman PHP 7 dan database MySQL. Untuk cara mengakses databasenya menggunakan MySQLi Extension dengan antarmuka Object Oriented.
	
Proses CRUD pada aplikasi ini menggunakan AJaX. AJaX adalah singkatan dari “Asynchronous JavaScript and XML“, merupakan suatu teknik pemrograman berbasis web untuk menciptakan aplikasi web interaktif yang lebih baik dan lebih cepat. Dengan menggunakan AJaX kita dapat melakukan pertukaran data dengan server di belakang layar, sehingga halaman web tidak harus dibaca ulang (refresh halaman) secara keseluruhan setiap kali seorang pengguna melakukan perubahan. Hal ini akan meningkatkan interaktivitas, kecepatan, dan usability.
 	
# Fitur Apilkasi 
1.	Create (Membuat request),
Membuat request untuk mengetahui summary data yang diinginkan.
2.	Read (Menampilkan Data),
Membuat script untuk membaca atau menampilkan data dari database MySQL ke aplikasi menggunakan JQuery DataTables Server-side Processing.
3.	Update (Mengubah Data),
Membuat script untuk mengubah/update data pada database MySQL melalui apilkasi menggunakan Bootstrap Modal dan AJaX.
4.	Delete (Menghapus Data),
Membuat script untuk menghapus/delete data pada database MySQL melalui aplikasi menggunakan AJaX dan SweetAlert untuk memunculkan notifikasi. 
5.	JQuery DataTables Server-side Processing,
Menggunakan JQuery DataTables Server-side Processing untuk membuat tabel yang dinamis dengan fitur cukup lengkap seperti filter, pagination, show perpage, dan sort by. DataTables Server-side Processing adalah salah satu cara terbaik untuk menampilkan data dari database dalam jumlah yang sangat besar. Dengan menggunakan metode ini, aplikasi yang memiliki banyak data, akan terasa ringan ketika di load.
6.	CSS Bootstrap 4,
Mendesain tampilan aplikasi menggunakan CSS Bootstrap 4. 
7.	SweetAlert,
Mempercantik tampilan alert JavaScript menggunakan SweetAlert untuk memunculkan notifikasi pada saat sukses menyimpan data, sukses mengubah data, konfirmasi hapus data, sukses menghapus data, dan validasi form input.
8.	Validasi Form Input,
Membuat validasi form input wajib diisi dengan memunculkan notifikasi menggunakan SweetAlert, Selain itu membuat fungsi untuk membatasi karakter yang diinputkan, bisa diinputkan huruf, angka atau karakter tertentu saja.
9.	Keamanan dasar untuk mencegah SQL injection,
Membuat keamanan dasar untuk mencegah SQL injection saat proses input data. Menghilangkan spasi dibelakang dan didepan kata yang diinputkan.
