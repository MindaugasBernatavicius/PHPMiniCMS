<?php
	if (!defined('included')){
		die('You cannot access this file directly!');
	}

	//log user in ---------------------------------------------------
	function login($conn, $user, $pass){
		//strip all tags from varible   
		$user = strip_tags(mysqli_real_escape_string($conn, $user));
		$pass = strip_tags(mysqli_real_escape_string($conn, $pass));

		$pass = md5($pass);

		// check if the user id and password combination exist in database
		$sql = "SELECT * FROM members WHERE username = '$user' AND password = '$pass'";
		$result = mysqli_query($conn, $sql) or die('Query failed. ' . mysql_error());
			
		if (mysqli_num_rows($result) == 1) {
			// the username and password match,
			// set the session
			$_SESSION['authorized'] = true;
						
			// direct to admin
			header('Location: '.DIRADMIN);
			exit();
		} else {
			// define an error message
			$_SESSION['error'] = 'Sorry, wrong username or password';
		}
	}

	// Authentication
	function logged_in() {
		if(isset($_SESSION['authorized']) and $_SESSION['authorized'] == true) {
			return true;
		} else {
			return false;
		}	
	}

	function login_required() {
		if(logged_in()) {	
			return true;
		} else {
			header('Location: '.DIRADMIN.'login.php');
			exit();
		}	
	}

	function logout(){
		unset($_SESSION['authorized']);
		header('Location: ' . DIRADMIN . 'login.php');
		exit();
	}

	// Render error messages
	function messages() {
		$message = '';
		if(isset($_SESSION['success']) and $_SESSION['success'] != '') {
			$message = '<div class="msg-ok">'.$_SESSION['success'].'</div>';
			$_SESSION['success'] = '';
		}
		if(isset($_SESSION['error']) and $_SESSION['error'] != '') {
			$message = '<div class="msg-error">'.$_SESSION['error'].'</div>';
			$_SESSION['error'] = '';
		}
		echo "$message";
	}

	function errors($error){
		if (!empty($error)){
			$i = 0;
			while ($i < count($error)){
			$showError.= "<div class=\"msg-error\">".$error[$i]."</div>";
			$i ++;}
			echo $showError;
		}
	}
?>