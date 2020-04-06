<?php
	session_start();
	require('adminheader.php');
	require('db.php');
?>
<body onload="blank()">
	<script type="text/javascript">
		function blank(){
			document.getElementById("examiner-open").style.display = "none";
			document.getElementById("student-open").style.display = "none";
		}
	</script>
<?php
	require('adminbody.php');
	$getData= $conn -> prepare("SELECT * FROM projectassessment.studenttable WHERE matric=?");
	$getData ->bindParam(1,$_COOKIE['selected_matric']);
	$getData -> execute();
	while ($row = $getData->fetch()):
?>	
		<!-- StudentAssessment -->
		<div class="details" id="details">
			<h3>Student List / <?php echo $row['name']; ?></h3>
			<br>
			<div class="data">
				<i class="fa fa-user fa-4x ico detsIcon"></i>
				<span class="studentinfo">
					<p><b><?php echo $row['name'];?></b></p>
					<p><?php echo $row['matric'];?></p>
				</span>
				<span class="studentinfo">
					<p><b>Level</b></p>
					<p><?php echo $row['level'];?></p>
				</span>
				<span class="studentinfo">
					<p><b>CGPA</b></p>
					<p><?php echo $row['cgpa'];?></p>
				</span>
			</div>
			<br><br>
			<!-- selection test 1 -->
			<h4>Pending Assessments</h4>
			<br>
			<?php
			$getpend= $conn -> prepare("SELECT * FROM projectassessment.assessmenttable WHERE matric=?");
			$getpend ->bindParam(1,$_COOKIE['selected_matric']);
			$getpend -> execute();
			$getData= $conn -> prepare("SELECT * FROM projectassessment.studenttable WHERE matric=?");
			$getData ->bindParam(1,$_COOKIE['selected_matric']);
			$getData -> execute();
			while ($row = $getData->fetch()): 
				if($row['pending'] == 0){
						echo "None for now <br><br><br>";
					}
				for($i=0;$i<$row['pending'];$i++):
					 $col = $getpend->fetch();
			?>
			<div class="data">
				<span class="cover">
					<p class="detsTopic" id = "<?php echo 'a'.$i?>"><b><?php echo $col['topic'];?></b></p>
					<p><?php echo $col['created_at']; ?></p>
				</span>
				<span class="cover">
					<button type="submit" class="btn btn-light viewBtn">VIEW FILE</button>
				</span>
				<button type="button" class="btn btn-light assignBtn" <?php if($col['assigned'] == 1):?> disabled <?php endif;?> onclick="assignExaminer(<?php echo $i ?>)">ASSIGN EXAMINER</button>
			</div>
			<br>
			<?php endfor;endwhile; ?>
			<!-- selection test 2 -->
			<h4>Marked Assessments</h4>
			<br>
			<?php
			if($row['marked'] == 0){
				echo "None for now <br><br><br>";
			}
			for($i=0;$i<$row['pending'];$i++):
			?>
			<div class="data">
				<span >
					<p><b>Title of Marked Assignment</b></p>
					<p>By: <b>Dr. Fashina</b></p>
				</span>
				<span>
					<button type="submit" class="btn btn-light viewBtn">VIEW FILE</button>
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
	
	<div id="assign-examiner" class="account">
		<div class="popUp">
			<button id="close" onclick="closeAssign()">X</button><h4 class="shifth3" id="">Choose Examiner</h4>
			<br>
			<form method="post" action="" class="popalign" name="assignExaminerForm">
				 <label>Examiner</label>
				<div class="input-group flex-nowrap">
				  <select  class="form-control" aria-label="Username" aria-describedby="addon-wrapping" name="selectexaminer">
				  	<?php 
				  	$getexaminer= $conn -> prepare("SELECT * FROM projectassessment.examinertable");
					$getexaminer -> execute();
					while ($row = $getexaminer->fetch()):
					?>
				  	<option><?php echo $row['name']?></option>
				  	<?php endwhile;?>
				  </select>
				</div>
				<br>
				<button type="button" class="btn btn-light" >+ Add Examiner</button>
				<br><br>
				<button type="submit" class="btn btn-light assignformBtn" name="thisExaminer">ASSIGN</button>
			</form>
		</div>
	</div>

	<script type="text/javascript">
	function assignExaminer(n){
		alert(n);
		document.getElementById("assign-examiner").style.display = "block";
		document.getElementById("contain").style.opacity = "0.5";
		document.getElementById("contain").style.filter = "blur(3px)";
		document.getElementById("details").style.opacity = "0.5";
		document.getElementById("details").style.filter = "blur(3px)";
		x = document.getElementsByClassName('detsTopic')[n].id;
		alert(x);
		y = document.getElementById(x).innerHTML;
		y = y.substring(3).slice(0,-4);
		alert(y);
		createCookie('selected_topic',y,'1');
	}
	function closeAssign(){
		document.getElementById("assign-examiner").style.display = "none";
		document.getElementById("contain").style.opacity = "";
		document.getElementById("contain").style.filter = "";
		document.getElementById("details").style.opacity = "";
		document.getElementById("details").style.filter = "";
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
