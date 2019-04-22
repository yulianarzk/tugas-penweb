<?php
	// mulai session
	session_start();
	// merandom 2 bilangan integer 0 - 10 untuk x dan y
	$x = rand(0, 10);
	$y = rand(0, 10);
	// jika action.php ini diload dari tombol 'submit1' (dari halaman index.php),
	// maka simpan username dari index.php ke dalam cookie username
	if (isset($_POST['submit1'])){
	 	setcookie('username', $_POST['username'], time()+3600*24*7);
	 	header("Location: action.php");
	}

	// jika action.php ini diload dari tombol submit (dari halaman action.php sendiri)
	if (isset($_POST['submit'])){
		// jika jawaban hitungan benar, maka skor bertambah 5 dan status = true
		if ($_POST['x_old'] + $_POST['y_old'] == $_POST['hasil']){
			$_SESSION['score'] += 5;
			$status = true;
		} else {
			// jika jawaban hitungan salah, maka skor dan lives berkurang 1 serta status = false 
			$_SESSION['score'] -= 1;
			$_SESSION['lives'] -= 1;
			$status = false;
		}
	}
?>

<?php 
	if (isset($_POST['submit1'])) {
		$nama = $_FILES['photo']['name'];
		$lokasi = $_FILES['photo']['tmp_name'];	
		$namabaru = $_POST['username'] .$nama ;
		move_uploaded_file($lokasi, "photos/" .$namabaru);
		
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Crazy Math</title>
</head>
<body>
	<h1>Crazy Math</h1>
	<?php
		// tampilkan username dari cookie
		echo "<p>Username: ".$_COOKIE['username']."</p>";
		// tampilkan lives dan score dari session
		echo "<p>Lives: ".$_SESSION['lives']."</p>";
		echo "<p>Score: ".$_SESSION['score']."</p>";
	?>

	<?php
		// jika nilai status sudah ada
		if (isset($status)){
			// jika status = true, maka munculkan 'jawaban benar'
			if ($status == true){
				echo "<h3>Jawaban Anda benar</h3>";
			} else {
				// jika status = false, maka munculkan 'jawaban salah'
				echo "<h3>Jawaban Anda salah</h3>";
			}
		}
	?>

	<?php
		// jika lives = 0, maka game over
		if ($_SESSION['lives'] == 0){
			echo "<h2>Game Over !!!</h2>";
			echo "<p><a href='index.php'>Ulangi Lagi</a></p>";
			// simpan skor dan waktu main ke dalam cookie
			setcookie('score', $_SESSION['score'], time()+3600*24*7);
			setcookie('lasttime', date('d/m/Y H:i'), time()+3600*24*7);

			// bagian kode untuk insert data username, score, playtime ke tabel scores

			include 'dbconfig.php';

			$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			$query = "INSERT INTO scores (username, scores, playtime, foto) VALUES ('".$_COOKIE['username']."', ".$_SESSION['score'].", '".date('Y-m-d H:i:s')."', 'diupload oleh ".$_COOKIE['username']." pada ".date('Y-m-d H:i:s')."')";
			$hasil = mysqli_query($db, $query);


		} else {
	?>
	<form method="post" action="action.php">
		<?php
			// munculkan kedua bilangan random x dan y
			echo "$x + $y = ";
		?>
		<input type="hidden" name="x_old" value="<?php echo $x;?>">
		<input type="hidden" name="y_old" value="<?php echo $y;?>">
		<input type="text" name="hasil">
		<input type="submit" name="submit">
	</form>
	<?php
		}
	?>
</body>
</html>
