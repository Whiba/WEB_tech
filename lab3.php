<?php
	// РѕС‚РєСЂС‹РІР°РµРј СЃРµСЃСЃРёСЋ
	session_start(); 
	$file = 'myFile.txt';
	$itsOkToWriteFile = 1;
	$nameError = "";
	$emailError = "";
	$sexError = "";
	$ageError = "";
	
	$my_name = "";
	$my_age = "";
	$my_email = "";
	$my_sex = "";
	
	if (!isset($_SESSION['token'])){
		$_SESSION['token']=uniqid(md5(rand()), true);
	}
	
	// используем метод POST?
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// проверка на соответствие самому себе
		if ($_POST["My_key"] == $_SESSION['token']) {
			echo "KEY";
			// данные были отправлены формой?
			if($_POST["Submit"]){
				
				// проверяем имя
				if (empty($_POST['name']))
				{
					$nameError = "Пожалуйста, введите имя";
					$itsOkToWriteFile = 0;
				}
				else {
					$my_name = $_POST['name'];
					if ($my_name.count_chars > 15) {
						$nameError = "Слишком длинное имя";
						$itsOkToWriteFile = 0;
					}
					if (!ereg("^[А-Яа-я]{2,15}$",$my_name)) {
						$nameError = "Какое-то неправильное имя";
						$itsOkToWriteFile = 0;
					}
				}
				
				// проверяем почту
				if (empty($_POST['email']))
				{
					$emailError = "Пожалуйста, введите email";
					$itsOkToWriteFile = 0;
				}
				else {
					$my_email = $_POST['email'];
					if (!filter_var($my_email, FILTER_VALIDATE_EMAIL)) {
						$emailError = "Некорректный email";
						$itsOkToWriteFile = 0;
					}
				}
				
				// проверяем пол
				if (empty($_POST['sex'])) {
					$sexError = "Пожалуйста, укажите ваш пол";
					$itsOkToWriteFile = 0;
				}
				else {
					$my_sex = $_POST['sex'];
				}
				
				// проверяем возраст
				if (empty($_POST['age'])) {
					$ageError = "Пожалуйста, укажите ваш возраст";
					$itsOkToWriteFile = 0;
				}
				else {
					$my_age = $_POST['age'];
					if (!filter_var($my_age, FILTER_VALIDATE_INT)) {
						$ageError = "Некорректный возраст";
						$itsOkToWriteFile = 0;
					}
					if ($my_age < 5 || $my_age > 100) {
						$ageError = "Некорректный возраст";
						$itsOkToWriteFile = 0;
					}
				}
			}
		}
		
		if ($itsOkToWriteFile) {
			$data = 'Name: ' . $my_name . '; E-mail: ' . $_POST['email'] . '; Sex: ' . $my_sex . '; Age: ' . $my_age;
			file_put_contents($file, $data);
		}
		
		$name2=iconv("utf-8", "cp1251", $_POST['name']);
		echo 'My_key' , $_POST['My_key'], "\n";
		echo 'token' , $_SESSION['token'], "\n";
		
		echo ' Name ', $name2, ' Email ', $_POST['email'], ' Sex ', $_POST['sex'], ' Age ', $_POST['age'];
	}
	
	// Закрываем сессию
	//session_destroy();
?>