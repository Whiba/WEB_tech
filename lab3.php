<?php
	// подключаем класс Subscribe
	require ('lab5.php');
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
	$my_Form = "";
	$dbhost = "localhost";
	$dbuser = "AnaShu";
	$dbpass = ",tksqvbirf";
	$dbname = "webTech";
	
	// проверка назначен ли уникальный ключ
	if (!isset($_SESSION['token'])){ // установлен ли
		$_SESSION['token']=uniqid(md5(rand()), true); // генерируем уникальный ID из рандомного числа, закодированного в формат md5
	}
	
	// создаем соединение
	$sub = new Subscribe();
	$conError = $sub->ConnectToDB();
	/*$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	 проверка соединения
	if (mysqli_connect_errno()) {
		$conError = "Ошибка соединения с БД: " . mysqli_connect_error();
		exit();
	}*/
	
	if (empty($my_Form)) { 
		$my_Form = $sub->GenerateForm();
	}
	
	// используем метод POST? Если это не проверять то при обращении к элементам массива $_POST возникнет ошибка E_NOTICE, и результат будет NULL.
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
			file_put_contents($file, $data, FILE_APPEND); // FILE_APPEND - этот флаг позволяет, если файл создан, дописывать в конец файлаы 

			// загрузка в БД
			$res = $sub->Save($my_name, $my_email, $my_sex, $my_age);
			//$obj = $sub->GetAll();
			//$arr = $sub->ToArray($obj);
			//$str = $sub->ToStringAll($arr);
			//$res = $sub->SetDataByEmail('mod08@mail.ru', 'Фил', 'male', 12);
			/*$querry = $con->prepare("INSERT INTO subscribe (name, email, sex, age) VALUES (?, ?, ?, ?)");
			$querry->bind_param("sssd", $my_name, $my_email, $my_sex, $my_age); // "sssd" - это типы данных для каждого параметра по порядку
			$querry->execute(); // выполнить запрос
			$querry->close();*/
		}		
		//echo ' Name ', $my_name, ' Email ', $my_email, ' Sex ', $_POST['sex'], ' Age ', $_POST['age'];
	}
	
	// подставляем файл чтобы он считывался и выполнялся как php
	include ('lab2.html');
	
	// закрываем сессию
	//session_destroy();
	
	// закрываем соединение
	$obj = $sub->CutConnection();
	//$con->close();

// $_SERVER - это массив, содержащий информацию, такую как заголовки, пути и местоположения скриптов. Записи в этом массиве 
//  создаются веб-сервером.
// $_SESSION - это ассоциативный массив, содержащий переменные сессии, которые доступны для текущего скрипта. 	

// АССОЦИАТИВНЫЕ МАССИВЫ
// ассоциативный массив - это такой массив, в котором для обращения к элементу использую ключи, логически связанные со значениями.
// Причем на ключи не накладываются никакие ограничения: он может содержать пробелы, длина такой строки может быть любой. В массиве находятся
// соответствия key => value. Параметр key является необязательным. Если он не указан, PHP будет использовать предыдущее наибольшее значение 
// ключа типа integer, увеличенное на 1.

// МЕТОДЫ GET и POST
// GET - этот метод передачи переменных применяется в PHP для передачи переменных в файл при помощи адресной строки. То есть переменные передаются 
// сразу через адресную строку браузера. Для проверки работы метода GET достаточно просто добавить к ссылке на файл знак вопроса «?» и через 
// амперсанд «&» перечислить переменные с их значениями. Например так: http://dmitriydenisov.com/get.php?a=1&b=2&c=3 Теперь при переходе по этой 
// ссылке нам выведется результат выполнения файла с этими перемнными. Данный способ очень простой и не требует создания дополнительных файлов. Все 
// необходимые данные поступают прямо через адресную строку браузера.
// POST - этот способ позволяет скрыто передавать переменные с одного файла в другой. Объем передаваемых значений методом POST по умолчанию ограничен 
// и равен 8 Мбайт. Чтобы увеличить это значение нужно изменить директиву post_max_size в php.ini. Очевидно, что данный вид передачи лучше, так как
// пользователь не видит в адресной строке данных, которые были переданы.

// Если обратиться к несуществующему элементу массива:
//Попытка доступа к неопределенному ключу в массиве - это то же самое, что и попытка доступа к любой другой неопределенной переменной: будет 
//сгенерирована ошибка уровня E_NOTICE, и результат будет NULL. 
?>