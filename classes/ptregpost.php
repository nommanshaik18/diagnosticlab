<?php
 require_once('./../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your database connection code here

    // Extract form data
    extract($_POST);

    // Insert into the 'patients' table
    $sql = "INSERT INTO `patients` (client_id, firstname, middlename, lastname, gender, dob, contact, address, email)
            VALUES ('$client_id', '$firstname', '$middlename', '$lastname', '$gender', '$dob', '$contact', '$address', '$email')";

    // Perform the query
    if ($conn->query($sql)) {
        // If the query is successful
        $response = array(
            'status' => 'success',
            'message' => 'Patient registered successfully.',
            'patient_id' => $conn->insert_id  // Optionally, you can include the new patient's ID
        );
    } else {
        // If the query fails
        $response = array(
            'status' => 'error',
            'message' => 'An error occurred while registering the account.',
            'error' => $conn->error  // Optionally, you can include the MySQL error
        );
    }

    // Close your database connection here

    // Send JSON response
    echo json_encode($response);
    exit;
}
?>
