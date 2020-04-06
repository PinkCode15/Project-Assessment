<?php
require('db.php');
if(isset($_POST['submit'])){
	$id =strtolower(htmlentities($_POST['id'])) ;
	$password = strtolower(htmlentities($_POST['password'])) ;
	$getData= $conn -> prepare("SELECT * FROM projectassessment.examinertable WHERE idNo=?");
	$getData ->bindParam(1,$id);
	$getData -> execute();
	session_start();
	while ($row = $getData->fetch()) {
		if($row['idNo'] === $id && $row['password'] === $password){
			$_SESSION['login_user'] = explode(" ", $row['name'])[1];
			$_SESSION['login_id'] = $row['idNo'];
			echo $row['idNo'];
			header("location: examinerDashboard.php");
		}
		else{
			echo 'problem';
		}	
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Student Portal.Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
	<div class="container container-more">
		<div class="left-side">
			<img src="img/school_logo.png" id="logo">
			<h2>Examiner Portal</h1>
			<h6>Welcome. Login to Your Account</h6>
			<br>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label>Identification Number</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Id No." aria-label="Username" aria-describedby="addon-wrapping" name="id" required="required">
				</div>
				<br>
				<label>Password</label>
				<div class="input-group flex-nowrap">
				  <input type="password" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping" name="password" required="required">
				</div>
				<br>
				<div>
					<span id="check"><input type="checkbox" name="remember" id="inpcheck">Remember Me</span>
					<span id="forgot"><a href="#">Forgot Password?</a></span>
				</div>
				<br><br>
				<input type="submit" class="btn btn-secondary btn-lg btn-block" value="LOGIN" name="submit">
			</form>
		</div>

		<div class="right-side">
			<img src="img/school.png" id="school">
		</div>
	</div>
</body>
</html>