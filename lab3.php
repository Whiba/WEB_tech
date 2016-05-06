<?php
	// открываем сессию
	session_start();
	
	// переменные
	$file = 'myFile.txt';
	$itsOkToWriteFile = true;
	$nameError = "";
	$emailError = "";
	$sexError = "";
	$ageError = "";
	$conError = "";	
	$my_name = "";
	$my_age = "";
	$my_email = "";
	$my_sex = "";
	$dbhost = "localhost";
	$dbuser = "AnaShu";
	$dbpass = ",tksqvbirf";
	$dbname = "webTech";
	
	// проверка назначен ли уникальный ключ
	if (!isset($_SESSION['token'])){
		$_SESSION['token']=uniqid(md5(rand()), true);
	}
	
	// создаем соединение
	$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	// проверка соединения
	if (mysqli_connect_errno()) {
		$conError = "Ошибка соединения с БД: " . mysqli_connect_error();
		exit();
	}
	
	// используем метод POST?
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// проверка на соответствие самому себе
		if ($_POST["mykey"] == $_SESSION['token']) {
			// данные были отправлены формой?
			if($_POST["Submit"]){
				
				// проверяем имя
				if (empty($_POST['name']))
				{
					$nameError = "Пожалуйста, введите имя";
					$itsOkToWriteFile = false;
				}
				else {
					$my_name = $_POST['name'];
					if ($my_name.count_chars > 15) {
						$nameError = "Слишком длинное имя";
						$itsOkToWriteFile = false;
					}
					if (!preg_match("/^[А-Я][а-я]*/", $my_name)) {
						$nameError = "Некорректное имя";
						$itsOkToWriteFile = false;
					}
				}
				
				// проверяем почту
				if (empty($_POST['email']))
				{
					$emailError = "Пожалуйста, введите email";
					$itsOkToWriteFile = false;
				}
				else {
					$my_email = $_POST['email'];
					if (!filter_var($my_email, FILTER_VALIDATE_EMAIL)) {
						$emailError = "Некорректный email";
						$itsOkToWriteFile = false;
					}
				}
				
				// проверяем пол
				if (empty($_POST['sex'])) {
					$sexError = "Пожалуйста, укажите ваш пол";
					$itsOkToWriteFile = false;
				}
				else {
					$my_sex = $_POST['sex'];
				}
				
				// проверяем возраст
				if (empty($_POST['age'])) {
					$ageError = "Пожалуйста, укажите ваш возраст";
					$itsOkToWriteFile = false;
				}
				else {
					$my_age = $_POST['age'];
					if (!filter_var($my_age, FILTER_VALIDATE_INT)) {
						$ageError = "Некорректный возраст";
						$itsOkToWriteFile = false;
					}
					if ($my_age < 5 || $my_age > 100) {
						$ageError = "Некорректный возраст";
						$itsOkToWriteFile = false;
					}
				}
			}
		}
		
		if ($itsOkToWriteFile) {
			// загрузка в файл
			$data = 'Name: ' . $my_name . '; E-mail: ' . $my_email . '; Sex: ' . $my_sex . '; Age: ' . $my_age . "\n";
			file_put_contents($file, $data);
			
			// загрузка в БД
			$querry = $con->prepare("INSERT INTO subscribe (name, email, sex, age) VALUES (?, ?, ?, ?)");
			$querry->bind_param("sssd", $my_name, $my_email, $my_sex, $my_age); // "sssd" - это типы данных для каждого параметра по порядку
			$querry->execute(); // выполнить запрос
			$querry->close();
		}		
		//echo ' Name ', $my_name, ' Email ', $my_email, ' Sex ', $_POST['sex'], ' Age ', $_POST['age'];
	}
	
	// подставляем файл чтобы он считывался и выполнялся как php
	include ('lab2.html');
	
	// закрываем сессию
	//session_destroy();
	
	// закрываем соединение
	$con->close();
?>