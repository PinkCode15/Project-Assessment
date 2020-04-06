<?php
	require('db.php');
	session_start(); 
	// var_dump($_SESSION);
	// echo $_SESSION['name'];
	if(!isset($_SESSION['login_user'])){ 
	  header("location: examinerLogin.php"); // Redirecting To Home Page 
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Student Portal.Examiner Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/examinerDashboard.css">
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
			    	<p id="user">Hello <b><?php echo $_SESSION['login_user']; ?></b>!</p>
			      	<a class="dropdown-item" href="#">Edit Profile</a>
			      	<a class="dropdown-item" href="examinerLogout.php">Sign Out</a>
			    </div>
		  </div>
		</nav>
		<div class="large">
			<div class="btn-group sublarge" role="group" aria-label="Basic example">
				<button type="button" class="btn add" id="student"><i class="fa fa-book ico"></i>Assessments</button>
			</div>
		</div>
		<div class="details" id="details">
			<!-- selection test 1 -->
			<h4>Pending Assessments</h4>
			<br>
			<?php
				$getpend= $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=?");
				$getpend ->bindParam(1,$_SESSION['login_id']);
				$getpend -> execute();
				$getData= $conn -> prepare("SELECT * FROM projectassessment.examinertable WHERE idNo=?");
				$getData ->bindParam(1,$_SESSION['login_id']);
				$getData -> execute();
				while ($row = $getData->fetch()): 
					if($row['pending'] == 0){
						echo "None for now <br><br><br>";
					}
					for($i=0;$i<$row['pending'];$i++):
			?>
			<div class="data">
				<span>
					<p class="detsTopic" id = "<?php echo 'a'.$i?>"><b><?php $col = $getpend->fetch(); echo $col['topic']; ?></b></p>
					<p><?php echo $col['created_at']; ?></p>
				</span>
				<span>
					<button type="submit" class="btn btn-light viewBtn" onclick="gradeAssessment(<?php echo $i ?>)">EXAMINE</button>
				</span>
			</div>
		<?php endfor;endwhile;?>
		</div>
	</div>
	<div id="grade-assessment" class="account">
		<div class="popUp">
			
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<button id="close" onclick="closeAssess()">X</button><h3 class="shifth3" id="subject">Abstract</h3>
			<br>
		<div id="abstractText"  style="display: none;">
			<div class="extractedText">
				<?php 
					$idNo = $_SESSION['login_id'];
					$subject = strtolower($_COOKIE['selected_subject']);
					$topic = $_COOKIE['selected_topic'];
					$getData = $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=? and topic=?");
					$getData ->bindParam(1,$idNo);
					$getData ->bindParam(2,$topic);
					$getData -> execute();
					$row = $getData->fetch();
					echo $row['abstract'] ;
					?>
			</div>
			<br>
			<div class="input-group inpscore flex-nowrap">
				<label class="scoretext">Score</label>
				<input type="text" class="form-control" placeholder="Score" aria-label="Username" aria-describedby="addon-wrapping" name="absscore" id="score" required="required">
			</div>
		</div>
		<div id="literatureText"  style="display: none;">
			<div class="extractedText" >
				<?php 
					$idNo = $_SESSION['login_id'];
					$subject = strtolower($_COOKIE['selected_subject']);
					$topic = $_COOKIE['selected_topic'];
					$getData = $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=? and topic=?");
					$getData ->bindParam(1,$idNo);
					$getData ->bindParam(2,$topic);
					$getData -> execute();
					$row = $getData->fetch();
					echo $row['literature'] ;
					?>
			</div>
			<br>
			<div class="input-group inpscore flex-nowrap">
				<label class="scoretext">Score</label>
				<input type="text" class="form-control" placeholder="Score" aria-label="Username" aria-describedby="addon-wrapping" name="litscore" id="score" required="required">
			</div>
		</div>
		<div id="methodologyText"  style="display: none;">
			<div class="extractedText" >
				<?php 
					$idNo = $_SESSION['login_id'];
					$subject = strtolower($_COOKIE['selected_subject']);
					$topic = $_COOKIE['selected_topic'];
					$getData = $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=? and topic=?");
					$getData ->bindParam(1,$idNo);
					$getData ->bindParam(2,$topic);
					$getData -> execute();
					$row = $getData->fetch();
					echo $row['methodology'] ;
					?>
			</div>
			<br>
			<div class="input-group inpscore flex-nowrap">
				<label class="scoretext">Score</label>
				<input type="text" class="form-control" placeholder="Score" aria-label="Username" aria-describedby="addon-wrapping" name="metscore" id="score" required="required">
			</div>
		</div>
		<div id="analysisText"  style="display: none;">
			<div class="extractedText" >
				<?php 
					$idNo = $_SESSION['login_id'];
					$subject = strtolower($_COOKIE['selected_subject']);
					$topic = $_COOKIE['selected_topic'];
					$getData = $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=? and topic=?");
					$getData ->bindParam(1,$idNo);
					$getData ->bindParam(2,$topic);
					$getData -> execute();
					$row = $getData->fetch();
					echo $row['analysis'] ;
					?>
			</div>
			<br>
			<div class="input-group inpscore flex-nowrap">
				<label class="scoretext">Score</label>
				<input type="text" class="form-control" placeholder="Score" aria-label="Username" aria-describedby="addon-wrapping" name="anascore" id="score" required="required">
			</div>	
		</div>
		<div id="conclusionText"  style="display: none;">
			<div class="extractedText" >
				<?php 
					$idNo = $_SESSION['login_id'];
					$subject = strtolower($_COOKIE['selected_subject']);
					$topic = $_COOKIE['selected_topic'];
					$getData = $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE idNo=? and topic=?"
				);
					$getData ->bindParam(1,$idNo);
					$getData ->bindParam(2,$topic);
					$getData -> execute();
					$row = $getData->fetch();
					echo $row['conclusion'] ;
					?>
			</div>
			<br>
			<div class="input-group inpscore flex-nowrap">
				<label class="scoretext">Score</label>
				<input type="text" class="form-control" placeholder="Score" aria-label="Username" aria-describedby="addon-wrapping" name="conscore" id="score" required="required">
			</div>
		</div>
			<br>
			
			<br><br>
			<div class="btnGroup">
			<button type="submit" class="btn btn-light clickBtn" onclick="abstractAssessment()">Abstract</button>
			<button type="submit" class="btn btn-light clickBtn" onclick="literatureAssessment()">Literature Review</button>
			<button type="submit" class="btn btn-light clickBtn" onclick="methodologyAssessment()">Methodology</button>
			<button type="submit" class="btn btn-light clickBtn" onclick="analysisAssessment()">Analysis</button>
			<button type="submit" class="btn btn-light clickBtn" onclick="conclusionAssessment()">Conclusion</button>
			</div>
			<br>
			<?php
			if(isset($_POST['sendscore'])){
				echo "<h1>hello</h1>";
				header('Location: examinerLogin.php');
				$topic =htmlentities( $_COOKIE['selected_topic']);
				$idNo = $_SESSION['login_id'];
				$absscore = $_POST['absscore'];
				$litscore = $_POST['litscore'];
				$anascore = $_POST['anascore'];
				$metscore = $_POST['metscore'];
				$conscore = $_POST['conscore'];
				$getData = $conn -> prepare("INSERT INTO projectassessment.scoretable (topic,examiner,abstract,literature,analysis,methodology,conclusion) VALUES  (?,?,?,?,?,?,?)");
				$getData ->bindParam(1,$topic);
				$getData ->bindParam(2,$idNo);
				$getData ->bindParam(3,$absscore);
				$getData ->bindParam(4,$litscore);
				$getData ->bindParam(5,$anascore);
				$getData ->bindParam(6,$metscore);
				$getData ->bindParam(7,$conscore);
				$getData -> execute();
				//echo "hello2";
				$getData= $conn -> prepare("UPDATE projectassessment.studenttable SET marked= marked + 1 WHERE matric=?");
				$getData ->bindParam(1,$matric);
				$getData -> execute();
				$getData ->bindParam(2,$topic);
	}
			?>
			<button type="submit" class="btn btn-light viewBtn2" name="sendscore">Ok</button>
		</form>
		</div>
	</div>
	<script type="text/javascript">
		function gradeAssessment(n){
			document.getElementById("grade-assessment").style.display = "block";
			document.getElementById("contain").style.opacity = "0.5";
			document.getElementById("contain").style.filter = "blur(3px)";
			x = document.getElementsByClassName('detsTopic')[n].id;
			y = document.getElementById(x).innerHTML;
			y = y.substring(3).slice(0,-4);
			alert(y)
			createCookie('selected_topic',y,'1');
		}
		function closeAssess(){
			document.getElementById("grade-assessment").style.display = "none";
			document.getElementById("contain").style.opacity = "";
			document.getElementById("contain").style.filter = "";
		}
		function abstractAssessment(){
			document.getElementById("subject").innerHTML = "Abstract";
			x = document.getElementById("subject").innerHTML;
			createCookie('selected_subject',x,'1');
			document.getElementById("abstractText").style.display = "block";
			document.getElementById("literatureText").style.display = "none";
			document.getElementById("methodologyText").style.display = "none";
			document.getElementById("analysisText").style.display = "none";
			document.getElementById("conclusionText").style.display = "none";
		}
		function literatureAssessment(){
			document.getElementById("subject").innerHTML = "Literature Review";
			x = document.getElementById("subject").innerHTML;
			createCookie('selected_subject',x,'1');
			document.getElementById("abstractText").style.display = "none";
			document.getElementById("literatureText").style.display = "block";
			document.getElementById("methodologyText").style.display = "none";
			document.getElementById("analysisText").style.display = "none";
			document.getElementById("conclusionText").style.display = "none";
		}
		function methodologyAssessment(){
			document.getElementById("subject").innerHTML = "Methodology";
			x = document.getElementById("subject").innerHTML;
			createCookie('selected_subject',x,'1');
			document.getElementById("abstractText").style.display = "none";
			document.getElementById("literatureText").style.display = "none";
			document.getElementById("methodologyText").style.display = "block";
			document.getElementById("analysisText").style.display = "none";
			document.getElementById("conclusionText").style.display = "none";
		}
		function analysisAssessment(){
			document.getElementById("subject").innerHTML = "Analysis";
			x = document.getElementById("subject").innerHTML;
			createCookie('selected_subject',x,'1');
			document.getElementById("abstractText").style.display = "none";
			document.getElementById("literatureText").style.display = "none";
			document.getElementById("methodologyText").style.display = "none";
			document.getElementById("analysisText").style.display = "block";
			document.getElementById("conclusionText").style.display = "none";
		}
		function conclusionAssessment(){
			document.getElementById("subject").innerHTML = "Conclusion";
			x = document.getElementById("subject").innerHTML;
			createCookie('selected_subject',x,'1');
			document.getElementById("abstractText").style.display = "none";
			document.getElementById("literatureText").style.display = "none";
			document.getElementById("methodologyText").style.display = "none";
			document.getElementById("analysisText").style.display = "none";
			document.getElementById("conclusionText").style.display = "block";
		}
		function createCookie(name,value,days) {
		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime()+(days*24*60*60*1000));
		        var expires = "; expires="+date.toGMTString();
		    }
		    else var expires = "";
		    document.cookie = name+"="+value+expires+"; path=/;";
		}

</script>
</body>
</html>