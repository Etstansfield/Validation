<!DOCTYPE html>
<meta charset = "UTF-8">
<!-- 
Description: A small project to create and example of form handling, validation and MySQL interfacing in PHP
Author: Edward Stansfield
Date: June 2016
-->
<html>
	<head>
		<title>
			Form Validation and MySQL Example
		</title>
		<!-- Link to CSS -->
		<link rel="stylesheet" href="Form_Handling_Style.css"/>
	</head>
	<body>
		<div id="wrapper">
			<div id = "header">
				<h1>Form Validation and MySQL Handling</h1>
			</div>
			<br/>
			<!-- PHP Code to validate and handle data -->
			<?php

				//Define Error values, empty initially
				$fnameError = $snameError = $emailError = $genderError = $passwordError = $repeatError = $ageError = "";

					//define initial values of variables (all empty)
					$firstname = $surname = $email = $gender = $password = $repeatPassword = $age = "";
					
					//define error values, initially empty, these hold the error messages.
					$fnameError = $snameError = $emailError = $genderError = $passwordError = $repeatError = $ageError = "";
					
					//No need for repeatVal since both pass and repeat should be the same
					$fnameVal = $snameVal = $emailVal = $genderVal = $passVal = $ageVal = FALSE;
				
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						//Firstname
						if(empty($_POST['firstname'])){
							$fnameError = "First name is required!";
						}
						else{	//Non-empty - validate data
							$firstname = validate_data($_POST["firstname"]);
							$firstname = validate_fname($firstname);
						}
						//Surname
						if(empty($_POST['surname'])){
							$snameError = "Surname is required!";
						}
						else{
							$surname = validate_data($_POST["surname"]);
							$surname = validate_sname($surname);
						}
						//Email
						if(empty($_POST['email'])){
							$emailError = "Email is required!";
						}
						else{
							$email = validate_data($_POST["email"]);
							$email = validate_email($email);
						}
						
						if(empty($_POST['password'])){
							$passwordError = "Password is required!";
						}
						else{
							$password = validate_data($_POST["password"]);
						}
						if(empty($_POST['repeatPassword'])){
							$repeatError = "Reapeat of password required!";
						}
						else{
							$repeatPassword = validate_data($_POST["repeatPassword"]);
						}
						if(empty($_POST['age'])){
							$ageError = "Age is requried!";
						}
						else{
							$age = validate_data($_POST['age']);
							$age = validate_age($age);
						}
						if(!empty($_POST['password']) && !empty($_POST['repeatPassword'])){
							$password = validate_pass($password,$repeatPassword);
						}
						
						//don't need to check gender is non empty, by deafault it is female
						$gender = validate_data($_POST["gender"]);
						
						//Now check that all values have validated correctly and insert
						
						if($fnameVal == TRUE and $snameVal == TRUE and $emailVal == TRUE and $passwordVal == TRUE
						 and $ageVal == TRUE){
							echo "All data validated correctly!<br/>";
							
							//Connect to database and insert data
											//Connect to the database
							//This of course isn't secure, but fine for practise purposes
							$servername = "127.0.0.1";
							$username = "root";
							$password = "";
							$db_name = "personal_details";
							$table = "users";
							
							$con = mysqli_connect($servername, $username, $password,$db_name);

							//Error connecting to database
							if (!$con) {die("Unable to connect to MySQL: ".mysql_error());}
							else{
								//Do Nothing
								//echo "Succesful Connection!<br/>";
							}
							
							//encrypt the password
							//used fname as a salt so differnt for most users
							
							$password = crypt($password,$firstname);
							$repeatPassword = crypt($repeatPassword,$firstname);
							
							//insert the data
							//using prepared statements
							
							$insert = $con->prepare("INSERT INTO users (firstname,surname,email,password,age,gender) VALUES (?,?,?,?,?,?)");
							$insert->bind_param("ssssis",$firstname,$surname,$email,$password,$age,$gender);
							$insert->execute();
							$insert->close();
							//reset to empty, to prevent data being inserted twice
							$firstname = $surname = $email = $gender = $password = $repeatPassword = $age = "";

						}
						else{
							echo "Not all data entered correctly!<br/>";
						}
						
					}
					/*//Testing to see if data entered correctly
					//echo the entered data
					echo $firstname."<br/>";
					//Only text characters allowed
					
					echo $surname."<br/>";
					echo $email."<br/>";
					echo $gender."<br/>";
					echo $password."<br/>";
					echo $repeatPassword."<br/>";
					echo $age."<br/>";*/
					
					//function performs first round of validation, removes whitespace, slashes and removes HTML code
					//use $data from here on indiviual data to validate it.
					function validate_data($data){
						$data = trim($data);
						$data = stripslashes($data);
						$data = htmlspecialchars($data);
						return $data;
					}
					
					function validate_fname($data){
						if(preg_match_all("/^[a-zA-Z]*$/",$data)){
							//echo "Match!<br/>";
							//Do Nothing
							global $fnameVal;
							$fnameVal = TRUE;
							return $data;
						}
						else{
							global $fnameError;
							$fnameError = "Only Textual Characters are allowed!";
						}
					}
					function validate_sname($data){
						if(preg_match_all("/^[a-zA-Z]*$/",$data)){
							//echo "Match!<br/>";
							//Do Nothing
							global $snameVal;
							$snameVal = TRUE;
							return $data;
						}
						else{
							global $snameError;
							$snameError = "Only Textual Characters are allowed!";
						}
					}
					
					function validate_email($email){
						//must be; text/numbers @ text/num . text
						$emailPattern = "/[a-zA-Z0-9_\-\.+]+@[a-zA-Z0-9]+\.[a-zA-z]+/";
						
						if(preg_match_all($emailPattern,$email)){
							global $emailVal;
							$emailVal = TRUE;
							return $email;
						}
						else{
							global $emailError;
							$emailError = "Email must be of the form: name@address.domain";
						}
					}
					
					function validate_age($age){
						$age = (int)$age;
						if($age>125){
							global $ageError;

							$ageError = "Age cannot be greater than 125!";
						}
						else if($age<18){
							global $ageError;
							$ageError = "Age cannot be less than 18!";
							
						}
						else{
							global $ageVal;
							$ageVal = TRUE;
							return $age;
						}
					}
					
					function validate_pass($password,$repeatPassword){
						//Check passwords are identical first, then check for correct format
						//this pattern not working
						$passwordPattern = "/^[0-9a-zA-Z]{5,}$/";
						
						if($password === $repeatPassword){
							if(preg_match_all($passwordPattern,$password)){
								global $passwordVal;
								$passwordVal = TRUE;
								//$passwordError = "CORRECT!";
								return $password;
								
							}
							else{
								$passwordError = "Password must have at least one number, and be at least 5 characters long!";
							}
						}
						else{
							global $repeatError,$passwordError;
							$repeatError = $passwordError = "Passwords must be identical!";
						}
					}
					//Call this function on attempting to access database
					
				?>
				<!-- Pass Form Data to same document -->
			<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				FirstName: <input type = "text" name = "firstname"/> <span id = "firstnameError" class = "errors"><?php echo $fnameError;?></span><br/>
				Surname: <input type = "text" name = "surname"/> <span id = "surnameError" class = "errors"><?php echo $snameError;?></span><br/>
				Gender: <select name = "gender">
							<option value = "Female">
								Female
							</option>
							<option value = "Male">
								Male
							</option>
						</select> <span id = "genderError" class = "errors"></span> <br/><br/>
				Email Address: <input type = "text" name = "email"/> <span id = "emailError" class = "errors"><?php echo $emailError;?></span><br/>
				Password: <input type = "password" name = "password"/> <span id = "passwordError" class = "errors"><?php echo $passwordError;?></span><br/>
				Repeat Password: <input type = "password" name = "repeatPassword"/> <span id = "repeatPasswordError" class = "errors"><?php echo $repeatError;?></span><br/>
				Age: <br/><input type = "int" name = "age" maxlength = "3"/> <span id = "ageError" class = "errors"><?php echo $ageError;?></span><br/><br/>
				
				<input type = "submit"/><br/>
			</form>
			<!-- Here use SQL SELECT to grab data and echo it off in tabular format -->
			<?php
				
				//Connect to the database
				//This of course isn't secure, but fine for practise purposes
				$servername = "127.0.0.1";
				$username = "root";
				$password = "";
				$db_name = "personal_details";
				$table = "users";
				
				$con = mysqli_connect($servername, $username, $password,$db_name);

				//Error connecting to database
				if (!$con) {die("Unable to connect to MySQL: ".mysql_error());}
				else{
					//Do Nothing
					//echo "Succesful Connection!<br/>";
				}
				
				//create queries to select all data
				$fnameQuery = "SELECT firstname FROM users ORDER BY ID";
				$snameQuery = "SELECT surname FROM users ORDER BY ID";
				$emailQuery = "SELECT email FROM users ORDER BY ID";
				$passwordQuery = "SELECT password FROM users ORDER BY ID";
				$ageQuery = "SELECT age FROM users ORDER BY ID";
				$genderQuery = "SELECT gender FROM users ORDER BY ID";
				
				$fnameArray = array();
				$snameArray = array();
				$emailArray = array();
				$passwordArray = array();
				$ageArray = array();
				$genderArray = array();
				
				//query the database using prepared statements to prevent sql injection
				$fQuery = $con->prepare($fnameQuery);
				$fQuery->execute();
				$fQuery->bind_result($fnameResult);
				//$pQuery->fetch();
				//echo $fnameResult;
				
				//add the results to the array
				$i = 0;
				while ($fQuery->fetch()) {
					$fnameArray[$i] = $fnameResult;
					$i = $i + 1;
					//array_push($fnameArray,$fnameResult);
					//printf ("%s", $fnameResult);
				}
				//echo $fnameArray[1];
				
				//close the statement, need to do this before another statement can be used
				$fQuery->close();
				
				$sQuery = $con->prepare($snameQuery);
				$sQuery->execute();
				$sQuery->bind_result($snameResult);
				$i = 0;
				while ($sQuery->fetch()) {
					//array_push($snameArray,$snameResult);
					$snameArray[$i] = $snameResult;
					$i = $i + 1;
				}
				
				$sQuery->close();
				$i = 0;
				$eQuery = $con->prepare($emailQuery);
				$eQuery->execute();
				$eQuery->bind_result($emailResult);
				
				while ($eQuery->fetch()) {
					//array_push($emailArray,$emailResult);
					$emailArray[$i] = $emailResult;
					$i = $i + 1;
				}
				
				$eQuery->close();
				
				$i = 0;
				$pQuery = $con->prepare($passwordQuery);
				$pQuery->execute();
				$pQuery->bind_result($passResult);
				
				while ($pQuery->fetch()) {
					//array_push($passwordArray,$passResult);
					$passwordArray[$i] = $passResult;
					$i = $i + 1;
				}
				
				$pQuery->close();
				
				$i = 0;
				$aQuery = $con->prepare($ageQuery);
				$aQuery->execute();
				$aQuery->bind_result($ageResult);
				
				while ($aQuery->fetch()) {
					//array_push($ageArray,$ageResult);
					$ageArray[$i] = $ageResult;
					$i = $i + 1;
				}
				
				$aQuery->close();
				
				$i = 0;
				$gQuery = $con->prepare($genderQuery);
				$gQuery->execute();
				$gQuery->bind_result($genderResult);
				
				while ($gQuery->fetch()) {
					//array_push($genderArray,$genderResult);
					$genderArray[$i] = $genderResult;
					$i = $i + 1;
				}
				
				$gQuery->close();
				//Display results

				//Create Table
				echo "<br/><table>";
				echo "<th>First Name</th><th>Surname</th><th>Email</th><th>Password</th><th>Age</th><th>Gender</th>";
				//Now echo out the array results
				$i = 0;
				
				while($i < count($fnameArray)){
					echo "<tr><td>".$fnameArray[$i]."</td><td>".$snameArray[$i]."</td><td>".$emailArray[$i]."</td><td>".$passwordArray[$i]."</td><td>".$ageArray[$i]."</td><td>".$genderArray[$i]."</td></tr>";
					$i = $i + 1;
				}
				echo"</table>";
				/* TESTING
				$i = 0;
				while($i < count($fnameArray)){
					echo $fnameArray[$i];
					$i = $i + 1;
				}
				echo "<br/>";
				$i = 0;
				while($i < count($snameArray)){
					echo $snameArray[$i];
					$i = $i + 1;
				}
				echo "<br/>";
				$i = 0;
				while($i < count($emailArray)){
					echo $emailArray[$i];
					$i = $i + 1;
				}
			*/
				
			?>
			<div id = "footer">
				<br/><hr/><br/>
				Made by Edward Stansfield
				<span style = "float:right">Local Time:  
					<script type="text/javascript">
						document.write( Date() );
					</script>
				</span>
			</div>
		<!-- End of wrapper Div -->
		</div>
	</body>
</html>