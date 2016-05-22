<?php
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
?>