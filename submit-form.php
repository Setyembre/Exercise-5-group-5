<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysecdb"; // Ensure this matches your actual database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request to insert data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form inputs
    $lastname = htmlspecialchars($_POST['lastname']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $middlename = htmlspecialchars($_POST['middlename']);
    $email = htmlspecialchars($_POST['email']);
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $age = (int)$_POST['age'];
    $gender = htmlspecialchars($_POST['gender']);

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Error: The email address '$email' is already registered. Please use a different email.";
    } else {
        // Prepare and bind for inserting new data
        $stmt = $conn->prepare("INSERT INTO users (lastname, firstname, middlename, email, contact_number, age, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssiis", $lastname, $firstname, $middlename, $email, $contact_number, $age, $gender);
        
        if ($stmt->execute()) {
            $message = "Data inserted successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="submit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Submit Form</title>
 
</head>
<body>
<div class="container">
        <!-- Form for POST request -->
        <form action="submit-form.php" method="post">
            <h2>Submit using POST</h2>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>
            <label for="middlename">Middle Name:</label>
            <input type="text" id="middlename" name="middlename">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="submit" value="Submit">
        </form>

        <!-- Display message if set -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Form for GET request -->
        <div class="get-data-form">
            <h2>Retrieve Data using GET</h2>
            <form action="get.php" method="get">
             
                <input type="submit" value="View Data">
            </form>
        </div>
    </div>
</body>
</html>