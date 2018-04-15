<?php
  session_start();
  //dev environment
  $con = new mysqli("localhost", "root", "parv1608", "internshub");
  // Check connection
  if ($con->connect_errno) {
		echo "Error - Failed to connect to MySQL: " . $con->connect_error;
		die();
	}


/**************************************************************
**************General Purpose Functions************************
***************************************************************/

  //Show Message and redirect user
  function showMessage($str,$redirectPage){
    ?>
    <script>
      alert('<?php echo $str; ?>');
      window.open('<?php echo $redirectPage; ?>','_self');
    </script>
    <?php
  }


  //Test Input
  function testInput($request){
    global $con;
    foreach($request as $key=>$val){
      $val = mysqli_real_escape_string($con, htmlentities($val));
      $request[$key] = $val;
    }
    return $request;
  }


  //Get all data from table
  function getAllDataFromTable($tableName){
    global $con;
    $query = "SELECT * FROM $tableName";
    $queryExec = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($queryExec))
      $rows[] = $row;
    return $rows;
  }

/**************************************************************
******Student Login/Regs/Auth/Session Related functions********
***************************************************************/

  //Check if user-email exist
  function checkEmailExist($email){
    global $con;
    $query = "SELECT * FROM students WHERE email = '$email'";
    $queryExec = mysqli_query($con, $query);
    if(mysqli_num_rows($queryExec) == 1){
      return 1;
    }
    else{
      return 0;
    }
  }


  //Check if valid session exist
  function checkValidSessionExist(){
    global $con;
    if(isset($_SESSION['user']) && isset($_SESSION['password_hash'])){
    	if(!empty($_SESSION['user']) && !empty($_SESSION['password_hash'])){
        $session = testInput($_SESSION);
    		$user_session = $session['user'];
    		$password_hash_session = $session['password_hash'];
    		$query = "SELECT * FROM students WHERE Email = '$user_session' AND Password = '$password_hash_session'";
    		$queryExec = mysqli_query($con, $query);
    		$rows = mysqli_num_rows($queryExec);
    		if($rows == 1){
          return 1;
    		}
        else{
          return 0;
        }
    	}
    	else{
        return 0;
    	}
    }
    else{
      return 0;
    }
  }


  //Get current session student data
  function getCurrentSessionStudentData(){
    global $con;
    $session = testInput($_SESSION);
		$user_session = $session['user'];
		$password_hash_session = $session['password_hash'];
		$query = "SELECT * FROM students WHERE Email = '$user_session' AND Password = '$password_hash_session'";
		$queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row;
  }


  //Authenticate user
  function authenticateUser(){
    if(!checkValidSessionExist()){
      showMessage("Invalid session, Please login first", "login.php");
    }
  }


  //generate hash using SHA-512
  function generateHash($str){
    return crypt($str, '$6$rounds=5000$');
  }


  //Register User
  function registerUser($request){
    global $con;
    $request = testInput($request);
    $firstName = $request['firstName'];
    $lastName = $request['lastName'];
    $email = $request['email'];
    $password = $request['password'];
    $confirmPassword = $request['confirmPassword'];
		$password_hash = generateHash($password);
    if(checkEmailExist($email)){
      showMessage("User email already exist, Try using different email", "register.php");
    }
    if(strcmp($password, $confirmPassword) == 0){
      $query = "INSERT INTO students(First_Name, Last_Name, Email, Password) VALUES (
        '$firstName',
        '$lastName',
        '$email',
        '$password_hash'
      )";
      $queryExec = mysqli_query($con, $query);
      if($queryExec){
        showMessage("User registered successfully, You can now login", "login.php");
      }
      else{
        showMessage("Some error occured, Try again", "register.php");
      }
    }
    else{
      showMessage("Passwords not matched, Try again", "register.php");
    }
  }


  //Login User
  function loginUser($request){
    global $con;
    $request = testInput($request);
    $userEmail = $request['userEmail'];
    $password = $request['password'];
    $password_hash = generateHash($password);
    $query = "SELECT * FROM students WHERE Email = '$userEmail' AND Password = '$password_hash'";
    $queryExec = mysqli_query($con, $query);
    $rows = mysqli_num_rows($queryExec);
    if($rows == 1){
      $_SESSION['user'] = $userEmail;
      $_SESSION['password_hash'] = $password_hash;
      showMessage("Login success", "myProfile.php");
    }
    else
      showMessage("Invalid login credentials, Try again", "login.php");
  }


  //Logout user
  function logoutUser(){
    if(isset($_SESSION['user']) && isset($_SESSION['password_hash'])){
    	if(!empty($_SESSION['user']) && !empty($_SESSION['password_hash'])){
    		unset($_SESSION['user']);
    		unset($_SESSION['password_hash']);
        showMessage("Logout success", "login.php");
    	}
    	else{
        showMessage("Invalid session, Please login first", "login.php");
    	}
    }
    else{
      showMessage("Invalid session, Please login first", "login.php");
    }
  }



/**************************************************************
******Student Internship/Application related functions********
***************************************************************/


  //Get internship details by internship id
  function getInternshipDetailsByInternshipId($internshipId){
    global $con;
    $query = "SELECT * FROM internships WHERE Internship_Id = '$internshipId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row;
  }

  //Get particular internship location by internship id
  function getInternshipLocationByInternshipLocationId($internshipLocationId){
    global $con;
    $query = "SELECT * FROM internship_locations WHERE Internship_Location_Id = '$internshipLocationId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row['Location'];
  }

  //Get particular internship duration details by internship duration id
  function getInternshipDurationByInternshipDurationId($internshipDurationId){
    global $con;
    $query = "SELECT * FROM internship_durations WHERE Internship_Duration_Id = '$internshipDurationId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row['Duration'];
  }

  //Get particular internship type details by internship type id
  function getInternshipTypeByInternshipTypeId($internshipTypeId){
    global $con;
    $query = "SELECT * FROM internship_type WHERE Internship_Type_Id = '$internshipTypeId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row['Type'];
  }

  //Get particular internship applications by internship id
  function getInternshipApplicationsByInternshipId($internshipId){
    global $con;
    $query = "SELECT * FROM internship_applications WHERE Internship_Id = '$internshipId'";
    $queryExec = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($queryExec))
      $rows[] = $row;
    return $rows;
  }

  //Get particular student favourites by student id
  function getStudentFavouriteInternshipsByStudentId($studentId){
    global $con;
    $query = "SELECT * FROM student_favourites WHERE Student_Id = '$studentId'";
    $queryExec = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($queryExec))
      $rows[] = $row;
    return $rows;
  }

  //Get count of no. of applications by internship id
  function getNoOfApplicantsByInternshipId($internshipId){
    global $con;
    $query = "SELECT * FROM internship_applications WHERE Internship_Id = '$internshipId'";
    $queryExec = mysqli_query($con, $query);
    $noOfApplicants = mysqli_num_rows($queryExec);
    return $noOfApplicants;
  }

  //Get rank wise order of candidates applied for particular internship
  function getRankWiseOrder($internshipId){
    global $con;
    $query = "SELECT * FROM internship_applications WHERE Internship_Id = '$internshipId' ORDER BY(Student_Internship_Score) DESC";
    $queryExec = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($queryExec)){
      $rows[] = $row;
    }
    return $rows;
  }

  //Get student details by student id
  function getStudentDetails($studentId){
    global $con;
    $query = "SELECT * FROM students WHERE Student_Id = '$studentId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row;
  }

  //Get particular student applied internships by student id
  function getStudentAppliedInternshipsByStudentId($studentId){
    global $con;
    $query = "SELECT * FROM internship_applications WHERE Student_Id = '$studentId' ORDER BY(Application_Id) DESC";
    $queryExec = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($queryExec))
      $rows[] = $row;
    return $rows;
  }

  //Get company name by company id
  function getCompanyNameByCompanyId($companyId){
    global $con;
    $query = "SELECT * FROM companies WHERE Company_Id = '$companyId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row['Company_Name'];
  }

  //Get company details by company id
  function getCompanyDetailsByCompanyId($companyId){
    global $con;
    $query = "SELECT * FROM companies WHERE Company_Id = '$companyId'";
    $queryExec = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($queryExec);
    return $row;
  }

  //Check if student already applied for internship
  function intershipAlreadyAppliedCheck($internshipId, $studentId){
    global $con;
    $query = "SELECT * FROM internship_applications WHERE Internship_Id = '$internshipId' AND Student_Id = '$studentId'";
    $queryExec = mysqli_query($con, $query);
    $check = mysqli_num_rows($queryExec);
    return $check;
  }

  //Check if internship is in students favourite list
  function internshipStudentFavouriteCheck($internshipId, $studentId){
    global $con;
    $query = "SELECT * FROM student_favourites WHERE Internship_Id = '$internshipId' AND Student_Id = '$studentId'";
    $queryExec = mysqli_query($con, $query);
    $check = mysqli_num_rows($queryExec);
    return $check;
  }
?>
