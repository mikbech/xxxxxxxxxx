<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$name = $course = $appointment = "";
$name_err = $course_err = $appointment_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var(trim($_POST["name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $name_err = 'Please enter a valid name.';
    } else{
        $name = $input_name;
    }
    
    // Validate course
    $input_course = trim($_POST["course"]);
    if(empty($input_course)){
        $course_err = 'Please your course.';     
    } else{
        $course = $input_course;
    }
    
  // Validate appointment
    $input_appointment = trim($_POST["appointment"]);
    if(empty($input_appointment)){
        $appointment_err = 'Please enter an appointment.';     
    } else{
        $appointment = $input_appointment;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($course_err) && empty($appointment_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO appointment (name, course, appointment) VALUES (:name, :course, :appointment)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':name', $param_name);
            $stmt->bindParam(':course', $param_course);
            $stmt->bindParam(':appointment', $param_appointment);
            
            // Set parameters
            $param_name = $name;
            $param_course = $course;
            $param_appointment = $appointment;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Setter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Appointment</h2>
                    </div>
                    <p>Please fill this form to enter an appointment.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($course_err)) ? 'has-error' : ''; ?>">
                            <label>Course</label>
                            <textarea name="course" class="form-control"><?php echo $course; ?></textarea>
                            <span class="help-block"><?php echo $course_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Appointment</label>
                            <input type="text" name="appointment" class="form-control" value="<?php echo $appointment; ?>">
                            <span class="help-block"><?php echo $appointment_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Add">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>