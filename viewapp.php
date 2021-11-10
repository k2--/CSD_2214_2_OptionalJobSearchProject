<?php
include_once 'dbconfig.php';
$flt_availability = "";
$flt_skills = "";
$flt_status = "";
$src_skills = "";
$src_lname  = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$flt_availability = trim(htmlspecialchars($_POST["flt_availability"]));
	$flt_skills = trim(htmlspecialchars($_POST["flt_skills"]));
	$flt_status = trim(htmlspecialchars($_POST["flt_status"]));
	$src_skills = trim(htmlspecialchars($_POST["src_skills"]));
	$src_lname = trim(htmlspecialchars($_POST["src_lname"]));
}
?>

<!--CSD2214 Optional Asgmt Job Search-Kadeem View Page-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>View Applicants Page</title>
	<style>
		table {
		  border-collapse: collapse;
		}
		h1, h3{
			margin: 10px;
			color: blue;
		}
		ol {
			margin-block: 0px;
			padding-left: 20px;
		}
		
		input.form, select.form{
			margin-left: 10px;
			margin-bottom: 5px;
		}
		
		label.form{
			display: inline-block;
			width: 100px;
			text-align: right;
		}
		select.form{
			display: inline-block;
			width: 170px;
		}
	</style>
	<script>
		"use strict";
		function $ (id){
			return document.getElementById(id); 
		}
		
		function resetForm() {
			$("flt_skills").value = "";
			$("flt_availability").value = "";
			$("flt_status").value = "";
			$("src_skills").value = "";
			$("src_lname").value = "";
		}
		
		window.onload = function() {
			$("btn_reset").onclick = resetForm; 
		}
	</script>
</head>
<body>
	<main>
	<h1>View Application for Job Posting</h1>
	<label><a href="newapp.php">New Applications</a></label>
	<br>
	
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="searchbar" id="searchbar">
		<h3>Search & Filter</h3>
		<label class='form' for="flt_skills">Skills:</label>
		<select class='form' id="flt_skills" name="flt_skills" maxlength="30" value=<?php echo $flt_skills?>>
			<option value="">All</option>
		<?php 
		$skills_sql="SELECT skill1 as skill FROM tbl_applicants UNION SELECT skill2 as skill FROM tbl_applicants UNION SELECT skill3 as skill FROM tbl_applicants";
		$skills_stmt = $conn->prepare($skills_sql);
		$skills_stmt->execute();
		$skills_result = $skills_stmt->get_result();
		$skills_num_rows = $skills_stmt->num_rows();
		while($row=$skills_result->fetch_assoc()){
			if($flt_skills == $row['skill']){
				echo "<option selected>{$row['skill']}</option>";
			}else{
				echo "<option>{$row['skill']}</option>";
			}
		}			
		$skills_stmt->close();
		?>
		</select>
		<label class='form' for="flt_availability">Availability:</label>
		<select class='form' id="flt_availability" name="flt_availability" maxlength="30">
			<option value="">All</option>
			<option value="F/T" <?php if($flt_availability == 'F/T'){echo("selected");}?> >Full-Time</option>
			<option value="P/T" <?php if($flt_availability == 'P/T'){echo("selected");}?> >Part-Time</option>
		</select>
		<label class='form' for="flt_status">Status:</label>
		<select class='form' id="flt_status" name="flt_status" maxlength="30">
			<option value="">All</option>
			<option <?php if($flt_status == "International Student"){echo("selected");}?> >International Student</option>
			<option <?php if($flt_status == "Graduate"){echo("selected");}?> >Graduate</option>
			<option <?php if($flt_status == "Student"){echo("selected");}?> >Student</option>
			<option <?php if($flt_status == "Permanent Resident"){echo("selected");}?> >Permanent Resident</option>
		</select>
		
		<br>
		
		<label class='form' for="src_skills">Skills:</label>
		<input class='form' type="text" id="src_skills" name="src_skills" maxlength="254" value=<?php echo $src_skills?>>
		<label class='form' for="src_lname">Last Name:</label>
		<input class='form' type="text" id="src_lname" name="src_lname" maxlength="254" value=<?php echo $src_lname?>>
		<br>
		<input class='form' type="submit" id="btn_submit" value="Apply Filter">
		<input class='form' type="button" id="btn_reset" value="Clear Selections">
		
		
	</form>
	<?php
		echo "<p>Applied Filters:</p>";
		echo "<ul>";
		if ($flt_availability != ""){
			echo "<li>Availability = '{$flt_availability}'</li>";
		}
		if ($flt_status <> ""){
			echo "<li>Status = '{$flt_status}'</li>";
		}
		if ($flt_skills <> ""){
			echo "<li>Skills = '{$flt_skills}'</li>";
		}
		if ($src_skills <> ""){
			echo "<li>Skills like '{$src_skills}'</li>";
		}
		if ($src_lname  != ""){
			echo "<li>Last Name like '{$src_lname}'</li>";
		}
		echo "</ul>";
	?>
	
	<table id="tbl_applicants" width="90%" border="1">
	<thead>
		<tr>	
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email Address</th>
			<th>Phone #</th>
			<th>Status</th>
			<th>Availability</th>
			<th>Top Skills</th>
			<th>Resume</th>
		</tr>
	</thead>
	<tbody id='tbl_appbody'>
    <?php	
		$tbl_sql="SELECT * FROM tbl_applicants 
				WHERE lastname like concat('%',?,'%') 
				AND (skill1 like concat('%',?,'%') OR skill2 like concat('%',?,'%') OR skill3 like concat('%',?,'%'))
				AND (skill1 = ? OR skill2 = ? OR skill3 = ? OR '' = ?)
				AND (availability = ? OR '' = ?)
				AND (status = ? OR '' = ?)" ;
		$tbl_stmt = $conn->prepare($tbl_sql);
		echo $conn->error;
				
		$tbl_stmt->bind_param("ssssssssssss", $src_lname, $src_skills, $src_skills, $src_skills, $flt_skills, $flt_skills, $flt_skills, $flt_skills, $flt_availability, $flt_availability, $flt_status, $flt_status);
		$tbl_stmt->execute();
		$tbl_result = $tbl_stmt->get_result();
		$tbl_num_rows = 0;
		while($row=$tbl_result->fetch_assoc()){
			echo "<tr>";
			echo "<td>{$row['firstname']}</td>";
			echo "<td>{$row['lastname']}</td>";
			echo "<td>{$row['email']}</td>";
			echo "<td>{$row['phone']}</td>";
			echo "<td>{$row['status']}</td>";
			echo "<td>{$row['availability']}</td>";
			echo "<td><ol>";
			echo "	<li>{$row['skill1']} ({$row['skill1year']} years)</li>";
			echo "	<li>{$row['skill2']} ({$row['skill2year']} years)</li>";
			echo "	<li>{$row['skill3']} ({$row['skill3year']} years)</li>";
			echo "</ol></td>";
			echo "<td><a href='Resumes/{$row['resumename']}' target='_blank'>view file</a></td>";
			echo "</tr>";
			$tbl_num_rows++;
		}
		if($tbl_num_rows == 0){
			echo "<tr><td colspan='8'>No Results found</td></tr>";
		}
		$tbl_stmt->close();
		$conn->close();
	?>
	</tbody>
    </table>
	</main>
</body>
</html>		