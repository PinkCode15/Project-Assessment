<?php
	require('db.php');
	session_start(); 
	// var_dump($_SESSION);
	// echo $_SESSION['name'];
	if(!isset($_SESSION['login_user'])){ 
	  header("location: studentLogin.php"); // Redirecting To Home Page 
	}
	if(isset($_POST['assessSubmit'])){
		header('Location: studentDashboard.php');
		$matric = $_SESSION['login_matric'];
		$topic = ucwords(htmlentities($_POST['topic'])) ;
		$abstract = htmlentities($_POST['abstractText']) ;
		$literature = htmlentities($_POST['literatureText']) ;
		$analysis = htmlentities($_POST['analysisText']);
		$method = htmlentities($_POST['methodText']);
		$conclude = htmlentities($_POST['concludeText']) ;
		$getData= $conn -> prepare("INSERT INTO projectassessment.assessmenttable (matric,topic,abstract,literature,analysis,methodology,conclusion) VALUES  (?,?,?,?,?,?,?)");
		$getData ->bindParam(1,$matric);
		$getData ->bindParam(2,$topic);
		$getData ->bindParam(3,$abstract);
		$getData ->bindParam(4,$literature);
		$getData ->bindParam(5,$analysis);
		$getData ->bindParam(6,$method);
		$getData ->bindParam(7,$conclude);
		$getData -> execute();

		$getData= $conn -> prepare("UPDATE projectassessment.studenttable SET pending= pending + 1 WHERE matric=?");
		$getData ->bindParam(1,$matric);
		$getData -> execute();
		//$getData ->bindParam(2,$topic);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Student Portal.Student Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/studentDashboard.css">
</head>
<body>
	<div class="container-more" id="contain">
		<nav class="navbar navbar-light nav-more">
		  <a class="navbar-brand" href="#">
		    <img src="img/school_logo.png" width="60" height="60" alt="">
		  </a>
		  <form class="form-inline my-2 my-lg-0 ">
		    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
		    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
	   	  </form>
	   	  <div class="btn-group drop-more" role = "group">
		   		<button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
		      	<i class="fa fa-user fa-2x" id="open-ico"></i>
			    </button>
			    <div class="dropdown-menu menu" aria-labelledby="btnGroupDrop1">
			    	<p id="user">Hello <b><?php echo $_SESSION['login_user']; ?></b></p>
			      	<a class="dropdown-item" href="#">Edit Profile</a>
			      	<a class="dropdown-item" href="studentLogout.php">Logout Out</a>
			    </div>
		  </div>
		</nav>
		<div class="large">
			<div class="btn-group sublarge" role="group" aria-label="Basic example">
				<button type="button" class="btn add" id="student"><i class="fa fa-book ico"></i>Assessments</button>
				<button type="button" class="btn btn-light add2" onclick="submitAssessment()">Submit Assessment</button>
			</div>
		</div>
		<div class="details" id="details">
			<!-- selection test 1 -->
			<h4>Pending Assessments</h4>
			<br>
			<?php
				$getpend= $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE matric=?");
				$getpend ->bindParam(1,$_SESSION['login_matric']);
				$getpend -> execute();
				$getData= $conn -> prepare("SELECT * FROM projectassessment.studenttable WHERE matric=?");
				$getData ->bindParam(1,$_SESSION['login_matric']);
				$getData -> execute();
				while ($row = $getData->fetch()): 
					if($row['pending'] == 0){
						echo "None for now <br><br><br>";
					}
					for($i=0;$i<$row['pending'];$i++):
			?>
			<div class="data">
				<span>
					<p><b><?php $col = $getpend->fetch(); echo $col['topic']; ?></b></p>
					<p><?php echo $col['created_at']; ?></p>
				</span>
				<span>
					<button type="submit" class="btn btn-light viewBtn">VIEW FILE</button>
				</span>
				<p class="awaiting">Awaiting Assessment</p>
			</div>
			<br>
		<?php endfor;?>
			<!-- selection test 2 -->
			<h4>Marked Assessments</h4>
			<br>
			<!-- for each marked assesssment -->
			<?php
			if($row['marked'] == 0){
						echo "None for now <br><br><br>";
			}
			for($i=0;$i<$row['marked'];$i++):
			?>
			<div class="data">
				<span >
					<p><b>Title of Marked Assignment</b></p>
					<p>By: <b>Dr. Fashina</b></p>
				</span>
				<span>
					<button type="submit" class="btn btn-light viewBtn2">VIEW FILE</button>
				</span>
				<span class="studentinfo2first">
					<p><b>Abstract</b></p>
					<p><center>20</center></p>
				</span>
				<span class="studentinfo2">
					<p><b>Literature Review</b></p>
					<p><center>20</center></p>
				</span>
				<span class="studentinfo2">
					<p><b>Methodology</b></p>
					<p><center>20</center></p>
				</span >
				<span class="studentinfo2">
					<p><b>Analysis</b></p>
					<p><center>20</center></p>
				</span>
				<span class="studentinfo2">
					<p><b>Conclusion</b></p>
					<p><center>20</center></p>
				</span>
			</div>
			<br>
			<?php endfor;endwhile;?>
		</div>
	</div>
	<div id="submit-assessment" class="account">
		<div class="popUp">
			<button id="close" onclick="closeSubmit()">X</button><h3 class="shifth3" id="">Submit Assessment</h3>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="popalign" name="submissionForm">
				<label>Project Topic</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Topic" aria-label="Username" aria-describedby="addon-wrapping" name="topic" id="topic" required="required">
				</div>
				<br>
				<div class="flexSubmit">
					<div class="underflex">
						<p>Abstract</p>
						<textarea name="abstractText"></textarea>
					</div>
					<div class="underflex">
						<p>Literature Review</p>
						<textarea name="literatureText"></textarea>
					</div>
					<div class="underflex">
						<p>Methodology</p>
						<textarea name="methodText"></textarea>
					</div>
					<div class="underflex">
						<p>Analysis</p>
						<textarea name="analysisText"></textarea>
					</div>
					<div class="underflex">
						<p>Conclusion</p>
						<textarea name="concludeText"></textarea>
					</div>
				</div>
				<button type="submit" class="btn btn-light assignformBtn" name="assessSubmit">SUBMIT</button>
			</form>
		</div>
	</div>
<script type="text/javascript">
	function submitAssessment(){
		document.getElementById("submit-assessment").style.display = "block";
		document.getElementById("contain").style.opacity = "0.5";
		document.getElementById("contain").style.filter = "blur(3px)";
	}
	function closeSubmit(){
		document.getElementById("submit-assessment").style.display = "none";
		document.getElementById("contain").style.opacity = "";
		document.getElementById("contain").style.filter = "";
	}
</script>
</body>
</html>