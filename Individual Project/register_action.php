<?php
include 'config.php';
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php

//STEP 1: Form data handling using mysqli_real_escape_string function to escape special characters for use in an SQL query,
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userPwd = mysqli_real_escape_string($conn, $_POST['userPwd']);
    $confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPwd']);

    // Validate pwd and confirmPwd
    if ($userPwd !== $confirmPwd) {
        die("Password and confirm password do not match.");
    }

    // STEP 2: Check if userEmail already exists
    $sql = "SELECT * FROM user WHERE userEmail='$userEmail' LIMIT 1";    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        echo "<p><b>Error:</b> User exists, please register a new user</p>";        
    } else {
        // User does not exist, insert new user record, hash the password        
        $pwdHash = trim(password_hash($userPwd, PASSWORD_DEFAULT)); 
        //echo $pwdHash;
        $sql = "INSERT INTO user (studentID, userName, userEmail, userPwd) VALUES ('$studentID','$userName','$userEmail', '$pwdHash')";
        if (mysqli_query($conn, $sql)) {
            echo "<p>New user record created successfully. Welcome <b>" . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . "</b></p>";            
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }    
    }
}

mysqli_close($conn);

?>
<p><a href="login.php"> | Login |</a></p>
</body>
</html>
