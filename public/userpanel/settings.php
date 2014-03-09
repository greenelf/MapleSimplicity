<?php 
if($_SESSION['id']){
	echo "
		<h2 class=\"text-center\">Account Settings <small>Powered by <a href=\"https://github.com/greenelf/maplebit\">MapleBit</a></small></h2><hr/>";
	if(!isset($_POST['modify'])){
		$query = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
		$row = $query->fetch_assoc();
		echo "
		<div class=\"alert alert-warning\">If you want to keep your current password, leave the password fields blank!</div>
		<form method=\"post\" role=\"form\">
			<b><abbr title=\"You can't change this!\">Username</abbr></b>
				".$row['name']."
		<div class=\"form-group\">
			<label for=\"cPassword\">Current Password</label>
			<input type=\"password\" class=\"form-control\" id=\"cPassword\" placeholder=\"Current Password\" name=\"current\" />
		</div>
		<div class=\"form-group\">
			<label for=\"nPassword\">New Password</label>
			<input type=\"password\" class=\"form-control\" id=\"nPassword\" placeholder=\"New Password\" name=\"password\" />
		</div>
		<div class=\"form-group\">
			<label for=\"coPassword\">Confirm Password</label>
			<input type=\"password\" class=\"form-control\" id=\"coPassword\" placeholder=\"Confirm Password\" name=\"copassword\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Email\">Email</label>
			<input type=\"email\" class=\"form-control\" id=\"Email\" placeholder=\"email@dot.com\" maxlength=\"50\" name=\"email\" value=\"".$row['email']."\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Birthday\">Birthday</label>
			<input type=\"text\" class=\"form-control\" id=\"Birthday\" placeholder=\"1990-01-01\" name=\"birth\" value=\"".$row['birthday']."\" />
		</div>
			<input type=\"submit\" name=\"modify\" class=\"btn btn-primary\" value=\"Modify &raquo;\" />
		</form><br/>";

	}else{
		$u = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'");
		$userz = $u->fetch_assoc();
		$current = $mysqli->real_escape_string($_POST['current']);
		$pass = $mysqli->real_escape_string($_POST['password']);
		$cpass = $mysqli->real_escape_string($_POST['copassword']);
		$email = $mysqli->real_escape_string($_POST['email']);
		$birth = $mysqli->real_escape_string($_POST['birth']);
		if($current){
			if(sha1($current) == $userz['password']){
				if($pass != $cpass){
					echo "<div class=\"alert alert-danger\">Passwords do not match.</div>";
				}else{
					if(strlen($pass) < 6){
						echo "<div class=\"alert alert-danger\">Your password must be between 6 and 12 characters.</div>";
					}elseif(strlen($pass) > 12){
						echo "<div class=\"alert alert-danger\">Your password must be between 6 and 12 characters.</div>";
					}else{
						$u = $mysqli->query("UPDATE `accounts` SET `password`='".sha1($pass)."' WHERE `name`='".$userz['name']."'");
						echo "<div class=\"alert alert-success\">Your changes have successfully been saved.</div>";
					}
				}
			}else{
				echo "<div class=\"alert alert-danger\">The password you have entered is incorrect.</div>";
			}
		}elseif($email == ""){
			echo "<div class=\"alert alert-danger\">Please supply an email address.</div>";
		}else{
			$u = $mysqli->query("UPDATE `accounts` SET `email`='".$email."',`birthday`='".$birth."' WHERE `name`='".$userz['name']."'");
			echo "<div class=\"alert alert-success\">Your changes have successfully been saved.</div>";
		}
	}
}else{
	header('Location: ?page=home');
}
?>
