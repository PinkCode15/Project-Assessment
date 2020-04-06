<?php
require('db.php');
if(isset($_POST['submitStudent'])){
	header('Location: adminDashboard.php');
	$name =ucwords(htmlentities($_POST['studentname'])) ;
	$matric = htmlentities($_POST['matric']) ;
	$level = htmlentities($_POST['level']) ;
	$gp = htmlentities($_POST['gp']) ;
	$password = strtolower(htmlentities($_POST['password'])) ;
	$getData= $conn -> prepare("INSERT INTO projectassessment.studenttable (name,matric,password,level,cgpa) VALUES  (?,?,?,?,?)");
	$getData ->bindParam(1,$name);
	$getData ->bindParam(2,$matric);
	$getData ->bindParam(3,$password);
	$getData ->bindParam(4,$level);
	$getData ->bindParam(5,$gp);
	$getData -> execute();
}
elseif(isset($_POST['submitExaminer'])){
	header('Location: adminDashboard.php');
	$name =ucwords(htmlentities($_POST['examinername'])) ;
	$idNo = htmlentities($_POST['id']) ;
	$rank = htmlentities($_POST['rank']) ;
	$mail = htmlentities($_POST['mail']) ;
	$password = strtolower(htmlentities($_POST['password'])) ;
	$getData= $conn -> prepare("INSERT INTO projectassessment.examinertable (name,idNo,password,email,rank) VALUES  (?,?,?,?,?)");
	$getData ->bindParam(1,$name);
	$getData ->bindParam(2,$idNo);
	$getData ->bindParam(3,$password);
	$getData ->bindParam(4,$mail);
	$getData ->bindParam(5,$rank);
	$getData -> execute();
}
else if(isset($_POST['thisExaminer'])){
		header('Location: studentAssessments.php');
		echo $_COOKIE['selected_topic'];
		$matric = $_COOKIE['selected_matric'];
		$examiner =$_POST['selectexaminer'] ;
		$getexaminer= $conn -> prepare("SELECT idNo FROM projectassessment.examinertable WHERE name=?");
		$getexaminer ->bindParam(1,$examiner);
		$getexaminer -> execute();
		$y = $getexaminer -> fetch();
		$idNo = $y['idNo'];
		$getData= $conn -> prepare("UPDATE projectassessment.assessmenttable SET idNo =? WHERE matric=? and topic =?");

		$getData ->bindParam(1,$idNo);
		$getData ->bindParam(2,$matric);
		$getData ->bindParam(3,$_COOKIE['selected_topic']);
		$getData -> execute();

		$getData= $conn -> prepare("UPDATE projectassessment.examinertable SET pending= pending + 1 WHERE idNo=?");
		$getData ->bindParam(1,$idNo);
		$getData -> execute();

		$x = 1;
		$getData= $conn -> prepare("UPDATE projectassessment.assessmenttable SET assigned = ? WHERE  matric=? and topic =?");
		$getData ->bindParam(1,$x);
		$getData ->bindParam(2,$matric);
		$getData ->bindParam(3,$_COOKIE['selected_topic']);
		$getData -> execute();
		// $getData= $conn -> prepare("UPDATE projectassessment.assessmenttable SET idNo =? WHERE matric=?, topic =?");
		// $getData ->bindParam(1,$idNo);
		// $getData ->bindParam(1,$matric);
		// $getData ->bindParam(1,$_COOKIE['selected_topic']);
		// 
		// 
	}
?>
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
			    	<b><p id="user">Hello <?php echo $_SESSION['login_user']; ?>!</p></b>
			      	<a class="dropdown-item" href="#">Edit Profile</a>
			      	<a class="dropdown-item" href="adminLogout.php">Log Out</a>
			    </div>
		  </div>
		</nav>
		<div class="large">
			<div class="btn-group sublarge" role="group" aria-label="Basic example">
				<button type="button" class="btn add" onclick="students()" id="student"><i class="fa fa-users ico"></i>Students</button>
				<button type="button" class="btn add" onclick="examiners()" id="examiner"><i class="fa fa-users ico"></i>Examiners</button>
				<button type="button" class="btn btn-light add2one" onclick="createAccount()">Create Account</button>
				<button type="button" class="btn btn-light add2" onclick="createGrade()">Create Grade System</button>
			</div>
		</div>
		<div id="student-open">
			<div class="">
				<h3>Student List</h3>
				<div class="openBody">
				<?php 
				$getData= $conn -> prepare("SELECT COUNT(*) FROM projectassessment.studenttable");
				$getData -> execute();
				$row = $getData->fetch(PDO::FETCH_NUM);
				$getData= $conn -> prepare("SELECT * FROM projectassessment.studenttable");
				$getData -> execute();
				for($i=0;$i<1;$i++): 
					$add = $getData->fetch();
				?>
					<div class="col" onclick="changeStudent(<?php echo $i ?>)">
						<span class="badge badge-light notifypend" title="pending"><?php echo $add['pending']; ?></span>
						<span class="badge badge-light notifymark" title="marked"><?php echo $add['marked']; ?></span>
						<br>
						<i class="fa fa-user fa-4x ico detsIcon"></i>
						<div class="nameno">
							<p class="detsName"><b><?php echo $add['name']; ?></b></p>
							<p class="detsNo" id = "<?php echo 'a'.$add['matric'] ?>"><?php echo $add['matric']; ?></p>
						</div>
						<div class="others">
							<div class="level">
								<p><b>Level</b></p>
								<p><?php echo $add['level']; ?></p>
							</div>
							<div class="gpval">
								<p><b>CGPA</b></p>
								<p><?php echo $add['cgpa']; ?></p>
							</div>
						</div>
					</div>
				<?php endfor; ?>
				</div>
			</div>
		</div>
		<div id="examiner-open">
			<div class="">
				<h3>Examiner List</h3>
				<div class="openBody">
					<?php 
					$getData= $conn -> prepare("SELECT COUNT(*) FROM projectassessment.examinertable");
					$getData -> execute();
					$row = $getData->fetch(PDO::FETCH_NUM);
					$getData= $conn -> prepare("SELECT * FROM projectassessment.examinertable");
					$getData -> execute();
					for($i=0;$i<$row[0];$i++): 
						$add = $getData->fetch();
					?>
					<div class="col" onclick="">
						<span class="badge badge-light notifypend" title="pending"><?php echo $add['pending']; ?></span>
						<!-- <span class="badge badge-light notifypend" title="pending">5</span>
						<span class="badge badge-light notifymark" title="marked">2</span> -->
						<br>
						<i class="fa fa-user fa-4x ico detsIcon"></i>
						<div class="nameno">
							<p class="detsName"><b><?php echo $add['name']; ?></b></p>
							<p class="detsNo2"><?php echo $add['idNo']; ?></p>
						</div>
						<div class="others">
							<div class="level">
								<p><b>Rank</b></p>
								<p><?php echo $add['rank']; ?></p>
							</div>
							<div class="gpval">
								<p><b>E-mail</b></p>
								<p><?php echo $add['email']; ?></p>
							</div>
						</div>
					</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
	<div id="create-account" class="account">
		<div class="popUp">
			<button id="close" onclick="closeAccount()">X</button><h3 class="shifth3">Create Account</h3>
			<br><br>
			<button type="button" class="btn btn-secondary btn-lg shift" onclick="createStudent()">STUDENT</button>
			<br><br><br>
			<h4 class="shifth4">OR</h4>
			<br><br>
			<button type="button" class="btn btn-secondary btn-lg shift" onclick="createExaminer()">EXAMINER</button>
		</div>
	</div>
	<div id="create-student" class="account">
		<div class="popUp2">
			<button id="close" onclick="closeStudent()">X</button><h4 class="shifthead" id="">Create Student Account</h4>
			<br>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="popalign" name="studentForm">
				 <label>Student Name</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Name" aria-label="Username" aria-describedby="addon-wrapping" name="studentname" required="required" id="studentname">
				</div>
				<br>
				<label>Matric Number</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Matric No." aria-label="Username" aria-describedby="addon-wrapping" name="matric" id="matric" required="required">
				</div>
				<br>
				<label>Level</label>
				<div class="input-group flex-nowrap">
				  <select  class="form-control" aria-label="Username" aria-describedby="addon-wrapping" name="level">
				  	<option>100</option>
				  	<option>200</option>
				  	<option>300</option>
				  	<option>400</option>
				  	<option>500</option>
				  </select>
				</div>
				<br>
				<label>CGPA</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Cgpa" aria-label="Username" aria-describedby="addon-wrapping" name="gp" required="required">
				</div>
				<br>
				<label>Password</label>
				<div class="input-group flex-nowrap">
				  <button type="button" class="btn btn-light genBtn" onclick="generatePassword()">Generate Password</button>
				  <input type="text" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping" name="password" required="required" readonly="true" id="studentpassword">
				</div>
				<br><br>
				<button type="submit" class="btn btn-light createBtn" name="submitStudent">CREATE</button>
			</form>
		</div>
	</div>
	<div id="create-examiner" class="account">
		<div class="popUp2">
			<button id="close" onclick="closeExaminer()">X</button><h4 class="shifthead" id="">Create Examiner Account</h4>
			<!-- <br> -->
			<form method="post" action="" class="popalign" name="examinerForm">
				 <label>Examiner Name</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Name" aria-label="Username" aria-describedby="addon-wrapping" name="examinername" required="required" id="examinername">
				</div>
				<br>
				<label>Id Number</label>
				<div class="input-group flex-nowrap">
				  <input type="text" class="form-control" placeholder="Id No." aria-label="Username" aria-describedby="addon-wrapping" name="id" id="id" required="required">
				</div>
				<br>
				<label>Rank</label>
				<div class="input-group flex-nowrap">
				  <select  class="form-control" aria-label="Username" aria-describedby="addon-wrapping" name="rank">
				  	<option>Prof.</option>
				  	<option>Dr.</option>
				  	<option>Mr</option>
				  	<option>Mrs</option>
				  	<option>Ms</option>
				  </select>
				</div>
				<br>
				<label>E-mail Address</label>
				<div class="input-group flex-nowrap">
				  <input type="email" class="form-control" placeholder="E-mail" aria-label="Username" aria-describedby="addon-wrapping" name="mail" required="required">
				</div>
				<br>
				<label>Password</label>
				<div class="input-group flex-nowrap">
				  <button type="button" class="btn btn-light genBtn" onclick="generatePassword2()">Generate Password</button>
				  <input type="text" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping" name="password" required="required" readonly="true" id="examinerpassword">
				</div>
				<br><br>
				<button type="submit" class="btn btn-light createBtn" name="submitExaminer">CREATE</button>
			</form>
		</div>
	</div>

	<script type="text/javascript">
		function students(){
			document.getElementById("student").style.backgroundColor = "#1e2f39";
			document.getElementById("examiner").style.backgroundColor = "#0a1d28";
			document.getElementById("student-open").style.display = "block";
			document.getElementById("examiner-open").style.display = "none";
			document.getElementById("details").style.display = "none";

		}
		function examiners(){
			document.getElementById("examiner").style.backgroundColor = "#1e2f39";
			document.getElementById("student").style.backgroundColor = "#0a1d28";
			document.getElementById("examiner-open").style.display = "block";
			document.getElementById("student-open").style.display = "none";
			document.getElementById("details").style.display = "none";
		}
		function createAccount(){
			document.getElementById("create-account").style.display = "block";
			document.getElementById("contain").style.opacity = "0.5";
			document.getElementById("contain").style.filter = "blur(3px)";
			document.getElementById("details").style.opacity = "0.5";
			document.getElementById("details").style.filter = "blur(3px)";
		}
		function closeAccount(){
			document.getElementById("create-account").style.display = "none";
			document.getElementById("contain").style.opacity = "";
			document.getElementById("contain").style.filter = "";
			document.getElementById("details").style.opacity = "";
			document.getElementById("details").style.filter = "";
		}
		function createStudent(){
			document.getElementById("create-account").style.display = "none";
			document.getElementById("create-student").style.display = "block";
			// document.getElementById("contain").style.opacity = "0.5";
			// document.getElementById("contain").style.filter = "blur(3px)";
		}
		function closeStudent(){
			document.getElementById("create-student").style.display = "none";
			document.getElementById("contain").style.opacity = "";
			document.getElementById("contain").style.filter = "";
			document.getElementById("details").style.opacity = "";
			document.getElementById("details").style.filter = "";
		}
		function createExaminer(){
			document.getElementById("create-account").style.display = "none";
			document.getElementById("create-examiner").style.display = "block";
			// document.getElementById("contain").style.opacity = "0.5";
			// document.getElementById("contain").style.filter = "blur(3px)";
		}
		function closeExaminer(){
			document.getElementById("create-examiner").style.display = "none";
			document.getElementById("contain").style.opacity = "";
			document.getElementById("contain").style.filter = "";
			document.getElementById("details").style.opacity = "";
			document.getElementById("details").style.filter = "";
		}
		function generatePassword(){
			x = document.getElementById("studentname").value;
			y = document.getElementById("matric").value;
			surname = x.toLowerCase().split(" ");
			result = surname[0] + y;
			document.getElementById("studentpassword").value = result;
		}
		function generatePassword2(){
			x = document.getElementById("examinername").value;
			y = document.getElementById("id").value;
			surname = x.toLowerCase().split(" ");
			result = surname[0] + y;
			document.getElementById("examinerpassword").value = result;
		}
		function changeStudent(n){
			x = document.getElementsByClassName('detsNo')[n].id.substring(1);
			createCookie('selected_matric',x,'1');
			// alert(n);
			// document.cookie="selected_matric=x; path=/;"
			//alert(sessionStorage.selected_matric);
			window.location.replace('studentAssessments.php');

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