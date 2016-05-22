<?php
$name_my = '{"name": ["Name: " ,"text", "[А-Яа-я]{2,15}", "Input your name"], 
"email": ["E-mail", "email", "[0-9a-z]+@[a-z]+\.[a-z]+", "mail@mail.com", "required"],
"sex": [
	"Sex: ", "male", "Male", "female", "Female"],
"age": ["Age", "text", "[0-9]{1,3}", 20, "required"],
"agree": ["checkbox", "1", "document.getElementById(\'submit\').disabled = !this.checked", "I agree to the processing of personal data"],
"mykey": ["hidden"],
"Submit": ["submit", "submit", "Send"]}';
?>