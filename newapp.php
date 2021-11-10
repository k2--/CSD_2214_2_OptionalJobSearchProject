<!--CSD2214 Optional Asgmt Job Search-Kadeem New Applicant Page-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>New Applicant Page</title>
	
	<style>
		main{
			border: 2px solid blue;
		}
		h1{
			margin: 10px;
			color: blue;
		}
		label.form{
			display: inline-block;
			width: 100px;
			text-align: right;
		}
		label.error{
			display: inline-block;
			width: 650px;
			text-align: center;
		}
		input.form, select.form{
			margin-left: 10px;
			margin-bottom: 5px;
		}
		.error{
			color:red;
		}
		#register, #reset_form{
			width: 200px;
		
		}
	</style>		
</head>
<body>
	<main>
		<?php
			include_once 'dbconfig.php';
			$txt_fname = '';
			$txt_lname = '';
			$txt_email = '';
			$txt_phone = '';
			$txt_status = '';
			$txt_availability = '';
			$txt_skill1 = '';
			$txt_skill1year = 0;
			$txt_skill2 = '';
			$txt_skill2year = 0;
			$txt_skill3 = '';
			$txt_skill3year = 0;
			$err_msg ='';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {	
				// get the post records
				$txt_fname = trim(htmlspecialchars($_POST['txt_fname']));
				$txt_lname = trim(htmlspecialchars($_POST['txt_lname']));
				$txt_email = trim(htmlspecialchars($_POST['txt_email']));
				$txt_phone = trim(htmlspecialchars($_POST['txt_phone']));
				$txt_status = trim(htmlspecialchars($_POST['txt_status']));
				$txt_availability = trim(htmlspecialchars($_POST['txt_availability']));
				$txt_skill1 = trim(htmlspecialchars($_POST['txt_skill1']));
				$txt_skill1year = (int)trim(htmlspecialchars($_POST['txt_skill1year']));
				$txt_skill2 = trim(htmlspecialchars($_POST['txt_skill2']));
				$txt_skill2year = (int)trim(htmlspecialchars($_POST['txt_skill2year']));
				$txt_skill3 = trim(htmlspecialchars($_POST['txt_skill3']));
				$txt_skill3year = (int)trim(htmlspecialchars($_POST['txt_skill3year']));
				// Check connection
				if (!$conn->connect_error) {
					// setup for move to resume folder 
					$file = rand(100,1000000)."-".$_FILES['file_resume']['name'];
					$file_loc = $_FILES['file_resume']['tmp_name'];
					$file_type = $_FILES['file_resume']['type'];
					$folder ="resumes/";
					// if file moved successful attempt db write
					if(move_uploaded_file($file_loc, $folder . $file)){	
						// prep SQL query
						$sql = "INSERT INTO tbl_applicants (Id, firstname, lastname, email, phone, status, availability, resumename, resumefiletype, skill1, skill1year, skill2, skill2year, skill3, skill3year) 
						VALUES ('0', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ssssssssssssss", $txt_fname, $txt_lname, $txt_email, $txt_phone, $txt_status, $txt_availability, $file, $file_type, $txt_skill1, $txt_skill1year, $txt_skill2, $txt_skill2year, $txt_skill3, $txt_skill3year);

						// execute query & perform action based on result 
						if ($stmt->execute()) {
							// if successfully 
							$txt_fname = '';
							$txt_lname = '';
							$txt_email = '';
							$txt_phone = '';
							$txt_status = '';
							$txt_availability = '';
							$txt_skill1 = '';
							$txt_skill1year = 0;
							$txt_skill2 = '';
							$txt_skill2year = 0;
							$txt_skill3 = '';
							$txt_skill3year = 0;
							$err_msg ='';
							?>
							<script>
								alert('New record created successfully');
							</script>
							<?php
						}else {
							// if failed remove file
							unlink($folder . $file);
							$err_msg ='Error: ' . $conn->error;
						}
						$stmt->close();
						$conn->close();
					}else{
						$err_msg ='Error while uploading file';
					}
				}else{
					$err_msg = 'DB Connection failed: ' . $conn->connect_error;
				}
			}	
		?>
		<h1>Submit Application for Job Posting</h1>
		<label><a href="viewapp.php">View All Applications</a></label>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="application_form" id="application_form" enctype="multipart/form-data">
			<label class='error'>Please complete the required(*) fields</label><br>
			<label class='form' for="txt_fname">First Name:</label>
			<input class='form' type="text" id="txt_fname" name="txt_fname" maxlength="254" value=<?php echo $txt_fname?>>
			<span class='error'>*</span>
			
			<label class='form' for="txt_lname">Last Name:</label>
			<input class='form' type="text" id="txt_lname" name="txt_lname" maxlength="254" value=<?php echo $txt_lname?>>
			<span class='error'>*</span>
			
			<br>
			
			<label class='form' for="txt_email">Email:</label>
			<input class='form' type="email" id="txt_email" name="txt_email" maxlength="254"  value=<?php echo $txt_email?>>
			<span class='error'>*</span>	
			
			<label class='form' for="txt_phone">Mobile Phone:</label>
			<input class='form' type="text" id="txt_phone" name="txt_phone" maxlength="30"  value=<?php echo $txt_phone?>>
			<span class='error'>*</span>
			
			<br>
			
			<label class='form' for="txt_status">Status:</label>
			<select class='form' id="txt_status" name="txt_status" maxlength="30">
				<option value="">Please select status type</option>
				<option <?php if($txt_status == "International Student"){echo("selected");}?> >International Student</option>
				<option <?php if($txt_status == "Graduate"){echo("selected");}?> >Graduate</option>
				<option <?php if($txt_status == "Student"){echo("selected");}?> >Student</option>
				<option <?php if($txt_status == "Permanent Resident"){echo("selected");}?> >Permanent Resident</option>
			</select>
			<span class='error'>*</span>
				
			<label class='form'>Availability:</label>
			<input class='form' type="radio" name="txt_availability" id="F/T" value="F/T" <?php if($txt_availability == "F/T"){echo("checked");}?> >Full-Time
			<input class='form' type="radio" name="txt_availability" id="P/T" value="P/T"  <?php if($txt_availability == "P/T" or $txt_availability == "" ){echo("checked");}?> >Part-Time
			<br>
			
			<label class='form'>Resume:</label>
			<input class='form' type="file" id="file_resume" name="file_resume">
			<span class='error'>*</span><br>
			
			<br>
			
			<label class='form' for="txt_skill1">Top Skill #1:</label>
			<input class='form' type="text" id="txt_skill1" name="txt_skill1" maxlength="50" value=<?php echo $txt_skill1 ?>>
			<span class='error'>*</span>
			<label class='form' for="txt_skill1year">Years Exp.:</label>
			<input class='form' type="number" id="txt_skill1year" name="txt_skill1year" value=<?php echo $txt_skill1year ?>>
			<span class='error'>*</span>
			<br>
						
			<label class='form' for="txt_skill2">Top Skill #2:</label>
			<input class='form' type="text" id="txt_skill2" name="txt_skill2" maxlength="50" value=<?php echo $txt_skill2 ?>>
			<span class='error'>*</span>
			<label class='form' for="txt_skill2year">Years Exp.:</label>
			<input class='form' type="number" id="txt_skill2year" name="txt_skill2year" value=<?php echo $txt_skill2year ?>>
			<span class='error'>*</span>
			<br>
			
			<label class='form' for="txt_skill3">Top Skill #3:</label>
			<input class='form' type="text" id="txt_skill3" name="txt_skill3" maxlength="50" value=<?php echo $txt_skill3 ?>>
			<span class='error'>*</span>
			<label class='form' for="txt_skill3year">Years Exp.:</label>
			<input class='form' type="number" id="txt_skill3year" name="txt_skill3year" value=<?php echo $txt_skill3year ?>>
			<span class='error'>*</span>
			<br>
			
			<label class='error' id='lbl_msg'><?php echo $err_msg ?> </label>
			<br>
			
			<input class='form' type="submit" id="btn_submit" value="Submit">
			<input class='form' type="button" id="btn_resetform" value="Reset">
			<br>
		</form>
	</main>
	<script>
		"use strict";
		function $ (id){
			return document.getElementById(id); 
		}
		
		function showMessage(message){
			$("lbl_msg").innerHTML = message;
		}
		
		function hasValue(input, message){
			if (input.value.trim() == "") {
				input.focus();
				showMessage(message);
				return false;
			}
			return true;
		}
		
		function validateEmail(input, requiredMsg, invalidMsg) {
			// check if not empty
			if (!hasValue(input, requiredMsg)) {
				return false;
			}
			// validate email format
			const emailRegex =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			const email = input.value.trim();
			if (!emailRegex.test(email)) {
				showMessage(invalidMsg);
				return false;
			}
			return true;
		}
		 		
		function resetForm() {
			alert("reset");
			$("application_form").reset();
			$("txt_fname").value  = '';
			$("txt_lname").value  = '';
			$("txt_email").value  = '';
			$("txt_phone").value  = '';
			$("txt_status").value  = '';
			$("txt_skill1").value  = '';
			$("txt_skill1year").value = 0;
			$("txt_skill2").value  = '';
			$("txt_skill2year").value = 0;
			$("txt_skill3").value  = '';
			$("txt_skill3year").value = 0;
			$("txt_fname").focus();
			
		}
		
		function submitForm(event){
			// Clear errors
			showMessage("");

			// validate the form
			let isValid = 
				hasValue($('txt_fname'), 'Please enter your First Name')&&
				hasValue($('txt_lname'), 'Please enter your Last Name')&&
				validateEmail($('txt_email'), 'An email is Required', 'Entered email is not in correctly formated')&&
				hasValue($('txt_phone'), 'Please enter your Phone number')&&
				hasValue($('txt_status'), 'Please select a status')&&
				hasValue($('file_resume'), 'Please upload your resume')&&
				hasValue($('txt_skill1'), 'Please enter your 1st top skill')&&
				hasValue($('txt_skill1year'), 'Year Experience for the 1st Skill is Required')&&
				hasValue($('txt_skill2'), 'Please enter your 2nd top skill')&&
				hasValue($('txt_skill2year'), 'Year Experience for the 2nd Skill is Required')&&
				hasValue($('txt_skill3'), 'Please enter your 3rd top skill')&&
				hasValue($('txt_skill3year'), 'Year Experience for the 3rd Skill is Required');
				
			// if not valid, stop form submit.
			if (!isValid) {
				event.preventDefault();
			}
		}
		
		window.onload = function() {
			$("txt_fname").focus();
			$("btn_resetform").onclick = resetForm; 
		}
		$("application_form").addEventListener('submit', submitForm);
	</script>
</body>
</html>	