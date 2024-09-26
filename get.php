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

// Check if 'id' is provided in the GET request
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to integer
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT lastname, firstname, middlename, email, contact_number, age, gender FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    $stmt->execute();
    $stmt->bind_result($lastname, $firstname, $middlename, $email, $contact_number, $age, $gender);
    
    if ($stmt->fetch()) {
        // Create the response message
        $message = "<h2>User Information</h2>
                    Name: " . htmlspecialchars($lastname) . ", " . htmlspecialchars($firstname) . " " . htmlspecialchars($middlename) . "<br>
                    Email: " . htmlspecialchars($email) . "<br>
                    Contact Number: " . htmlspecialchars($contact_number) . "<br>
                    Age: " . htmlspecialchars($age) . "<br>
                    Gender: " . htmlspecialchars($gender) . "<br>";
    } else {
        $message = "<h2>No data found for ID: " . htmlspecialchars($id) . "</h2>";
    }
    
    $stmt->close();
    
    // If the request is made via AJAX, return only the message
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        echo $message; // Send back the message as the response
        exit; // Stop further execution
    }
} else {
    $message = "<h2>ID parameter is missing.</h2>";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="get.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Retrieve Data</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Form for GET request -->
        <h2>Retrieve Data using GET</h2>
        <form id="getForm" method="get">
            <label for="id">Enter User ID:</label>
            <input type="number" id="id" name="id" required>
            <input type="submit" value="View Data">
        </form>

        <!-- Display message if set -->
        <div id="dataOutput"></div> <!-- Div to display fetched data -->

        <br><button id="goBackBtn">Go Back</button>
    </div>
    <script>
    $(document).ready(function() {
        // Handle GET form submission via AJAX
        $("#getForm").submit(function(event) {
            event.preventDefault(); // Prevent form from submitting normally

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data

            // Send the data using AJAX
            $.ajax({
                url: 'get.php', // The same PHP file handling the GET request
                type: 'GET',
                data: formData,
                success: function(response) {
                    // Display the response data in the output div
                    $("#dataOutput").html(response);
                },
                error: function(xhr, status, error) {
                    // Display error message
                    $("#dataOutput").html('<p>An error occurred: ' + error + '</p>');
                }
            });
        });

        // Add click event to the Go Back button to redirect to submit-form.php
        $("#goBackBtn").click(function() {
            window.location.href = "submit-form.php"; // Redirect to submit-form.php
        });
    });
    </script>
</body>
</html>
