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
			$querry->execute(); // выполнить запрос
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
		static function GenerateForm() {
			return '<html>
			<form action="lab3.php" method="post"> <!--novalidate-->
		<fieldset>
			<legend><b>Контактная информация</b></legend>
			<!--Имя может быть длиной от 2 до 15 символов, русскими буквами-->
			<label for="name">Имя </label><input type="text" name="name" pattern="[А-Яа-я]{2,15}" placeholder="Введите ваше имя" >
			<span><em><?=$nameError?></em></span><br />
			<!--Почта должна быть написана английскими буквами, а по середине иметь знак "собака"-->
			<label for="email">E-mail<em>* </em></label><input type="email" name="email" pattern="[0-9a-z]+@[a-z]+\.[a-z]+" placeholder="mail@mail.com" required>
			<span><em><?=$emailError?></em></span><br />
			<form>
				<label for="sex">Пол</label>
				<select name="sex" >
					<option value="male" > Мужской </option>
					<option value="female" > Женский </option>
				</select>
				<span><em><?=$sexError?></em></span>
			</form>
			<br />
			<label for="age">Возраст<em>* </em></label><input type="text" name="age" pattern="[0-9]{1,3}" placeholder="20" required>
			<span><em><?=$ageError?></em></span>
			<form>
				<input type="checkbox" name="agree" value="1" onchange="document.getElementById(\'submit\').disabled = !this.checked"> Согласен на обработку персональных данных<em>* </em>
			</form>
			<input type="hidden" name="mykey" value="<?=$_SESSION[\'token\']?>" />
			<span><em><?=$conError?></em></span><br />
			<input type="submit" id="submit" name="Submit" value="Отправить" disabled>
		</fieldset>	
	</form>
				</html>';
		}
	}
// $this является ссылкой на вызываемый объект, если ее не указывать то появится синтаксическая ошибка, так как 
// не понятно на какой именно объякт ссылаться
?>