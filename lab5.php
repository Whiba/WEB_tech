<?php
	Class Subscribe { // объявляю класс Subscribe
		
		// объявляем локальные переменные
		var $Name;
		var $Email;
		var $Sex;
		var $Age;
		var $Con;
		
		// SELECT все записи из таблицы
		public function GetAll() { 
			$querry = $this->Con->query("SELECT * FROM subscribe"); // делает обычный запрос
			return $querry;
		}
		
		// SELECT записи из выбранной колонки
		public function GetColumnByName($my_column) { 
			$querry = $this->Con->prepare("SELECT ? FROM subscribe");
			$querry->bind_param("s", $my_column);
			$querry->execute(); // выполнить запрос, сюда можно добавить проверку result 
			$data = mysqli_fetch_row(mysqli_use_result($querry));
			$querry->close();
			return $data;
		}
		
		// SELECT записи из таблицы по указанному e-mail
		public function GetDataByEmail($my_email) { 
			$this->Email = $my_email;
			$querry = $this->Con->prepare("SELECT name, email, sex, age FROM subscribe WHERE email=?");
			$querry->bind_param("s", $this->Email);
			$querry->execute(); // выполнить запрос 
			$querry->close();
			return $querry;
		}
		
		// UPDATE запись а таблице по указанному e-mail
		public function SetDataByEmail($my_email, $my_name, $my_sex, $my_age) { 
			$this->Name = $my_name;
			$this->Email = $my_email;
			$this->Sex = $my_sex;
			$this->Age = $my_age;
			$querry = $this->Con->prepare("UPDATE subscribe SET name=?, sex=?, age=? WHERE email=?");
			$querry->bind_param("ssds", $this->Name, $this->Sex, $this->Age, $this->Email);
			$querry->execute(); // выполнить запрос
			$querry->close();
		}
		
		// INSERT запись в таблицу
		public function Save($my_name, $my_email, $my_sex, $my_age) { 
			$this->Name = $my_name;
			$this->Email = $my_email;
			$this->Sex = $my_sex;
			$this->Age = $my_age;
			$querry = $this->Con->prepare("INSERT INTO subscribe (name, email, sex, age) VALUES (?, ?, ?, ?)");
			//echo $this->Name; 
			$querry->bind_param("sssd", $this->Name, $this->Email, $this->Sex, $this->Age); // "sssd" - это типы данных для каждого параметра по порядку
			$querry->execute(); // выполнить запрос
			$querry->close();
		}
		
		// DELETE запись из таблицы по e-mail
		public function DeleteByEmail($my_email) { 
			$this->Email = $my_email;
			$querry = $this->Con->prepare("DELETE FROM subscribe WHERE email=?");
			$querry->bind_param("s", $this->Email);
			$querry->execute(); // выполнить запрос
			$querry->close();
		}
		
		// DELETE все записи из таблицы
		public function DeleteAll() { 
			$querry = $this->Con->query("DELETE FROM subscribe"); // выполняет обычный запрос
		}
		
		// создаем соединение с базой данных, с случае удачи возвращается пустая строка, 
		// в случае неудачи возвращается текст ошибки
		public function ConnectToDB() {
			$this->Con = new mysqli("localhost", "AnaShu", ",tksqvbirf", "webTech");
			if (mysqli_connect_errno()) {
				$conErr = "Ошибка соединения с БД: " . mysqli_connect_error();
			}
			else {
				$conErr = "";
			}
			return $conErr;
		}
		
		// закрываем соединение с базой данных
		public function CutConnection() {
			$this->Con->close();
		}
		
		// преобразовать элементы класса в строку
		public function ToStringAll($rowsArray) { // принимает массив
			//$str = $this->Name . " " . $this->Email . " " . $this->Sex . " " . $this->Age . "\n";
			foreach($rowsArray as $row)
			{
				echo $row['name'] , " " , $row['email'] , " " , $row['sex'] , " " , $row['age'];
			}
		}
		
		public function ToArray($quer) // принимает результат sql-запроса
		{
			//$result = $querry->fetch_array(MYSQLI_ASSOC); // выбирает одну строку из результата запроса и преобразует ее в массив 
			//(простой MYSQLI_NUM или ассоциативный MYSQLI_ASSOC в зависимости от выставленного параметра)
			//при задании MYSQLI_BOTH функция создаст один массив, включающий атрибуты обоих вариантов. 
			while($row = mysqli_fetch_array($quer, MYSQLI_BOTH))
			{
				$result[] = $row;
			}
			return $result; // возвращает массив
		}
		
		// возвращает код формы для работы с данным классом
		// формат JSON строки: {"Имя_объекта": его значение в виде массива[значения массива записываем через ,]}
		// значения в массиве могут быть: числовые(целые, дробные с точкой), "строковые",
		// логические (true, false), [другие массивы], {другие объекты}, нулевое значение (null)
		static function GenerateForm() {
			//$form = require('build_form.php');
			//require('form_data.php');
			//$n = json_decode($name_my);
			//echo $n->name;
			//echo $form;
			require('form_data.php');
			$n = json_decode(stripslashes($name_my), true);
			$myName = "<label for=\"name\">" . $n["name"][0] . "</label><input type=\"" . $n["name"][1] . "\" name=\"name\" pattern=\"" . $n["name"][2] . "\" placeholder=\"" . $n["name"][3] . "\" >";
			$myEmail = "<label for=\"email\">" . $n["email"][0] . "<em>* </em></label><input type=\"" . $n["email"][1] . "\" name=\"email\" pattern=\"" . $n["email"][2] . "\" placeholder=\"" . $n["email"][3] . "\" " . $n["email"][4] . ">";
			$mySex = "<form><label for=\"sex\">" . $n["sex"][0] . "</label><select name=\"sex\" ><option value=\"" . $n["sex"][1] . "\" >" . $n["sex"][2] . "</option><option value=\"" . $n["sex"][3] . "\" > " . $n["sex"][4] . " </option></select> </form>";			
			$myAge = "<label for=\"age\">" . $n["age"][0] . "<em>* </em></label><input type=\"" . $n["age"][1] . "\" name=\"age\" pattern=\"" . $n["age"][2] . "\" placeholder=\"" . $n["age"][3] . "\" " . $n["age"][4] . ">";
			$myAgree = "<input type=\"" . $n["agree"][0] . "\" name=\"agree\" value=\"" . $n["agree"][1] . "\" onchange=\"" . $n["agree"][2] . "\"> " . $n["agree"][3] . "<em>* </em>";
			$myKey = "<input type=\"" . $n["mykey"][0] . "\" name=\"mykey\" value=\"" . $_SESSION['token'] . "\" />";
			$mySubmit = "<input type=\"" . $n["Submit"][0] . "\" id=\"" . $n["Submit"][1] . "\" name=\"Submit\" value=\"" . $n["Submit"][2] . "\" disabled>";
			//var_dump($n);

			return '<html>
					<form action="lab3.php" method="post"> 
					<fieldset>
						<legend><b>Information</b></legend>
						<!--Имя может быть длиной от 2 до 15 символов, русскими буквами-->' .
						$myName .
						'<span><em><?=$nameError?></em></span><br />
						<!--Почта должна быть написана английскими буквами, а по середине иметь знак "собака"-->' .
						$myEmail .
						'<span><em><?=$emailError?></em></span><br />' .
						$mySex .
							'<span><em><?=$sexError?></em></span>
						<br />' .
						$myAge .
						'<span><em><?=$ageError?></em></span>
						<form>' .
							$myAgree .
						'</form>' .
						$myKey .
						'<span><em><?=$conError?></em></span><br />' .
						$mySubmit .
					'</fieldset>	
				</form>
				</html>';
		}
	}
// $this является ссылкой на вызываемый объект, если ее не указывать то появится синтаксическая ошибка, так как 
// не понятно на какой именно объякт ссылаться
// декоратор
// поле - и его данные . Структура:
// {"name": [Имя ,text" , pattern="[А-Яа-я]{2,15}" placeholder="Введите ваше имя" ]} json
//  и походим по этому циклами собирая форму, проверяя валидацию, и т.д.
// execute - Возвращает TRUE в случае успешного завершения или FALSE в случае возникновения ошибки.
?>