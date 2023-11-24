<?php
require_once('./../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your database connection code here

    // Extract and sanitize form data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if an ID is present
    if (!empty($id)) {
        // Update the existing patient
        $sql = "UPDATE `patients` SET
                client_id = '$client_id',
                firstname = '$firstname',
                middlename = '$middlename',
                lastname = '$lastname',
                gender = '$gender',
                dob = '$dob',
                contact = '$contact',
                address = '$address',
                email = '$email'
                WHERE id = '$id'";
    } else {
        // Insert a new patient
        $sql = "INSERT INTO `patients` (client_id, firstname, middlename, lastname, gender, dob, contact, address, email)
                VALUES ('$client_id', '$firstname', '$middlename', '$lastname', '$gender', '$dob', '$contact', '$address', '$email')";
    }

    // Perform the query
    if ($conn->query($sql)) {
        // If the query is successful
        $response = array(
            'status' => 'success',
            'message' => isset($id) ? 'Patient updated successfully.' : 'Patient registered successfully.',
            'patient_id' => isset($id) ? $id : $conn->insert_id  // Optionally, you can include the new patient's ID
        );
    } else {
        // If the query fails
        $response = array(
            'status' => 'error',
            'message' => isset($id) ? 'An error occurred while updating the patient.' : 'An error occurred while registering the account.',
            'error' => $conn->error  // Optionally, you can include the MySQL error
        );
    }

    // Close your database connection here

    // Send JSON response
    echo json_encode($response);
    exit;
}


?>
