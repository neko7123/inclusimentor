<?php
session_start();
include("connection.php");

if(isset($_SESSION['mentee_id'])) {
    $mentee_id = mysqli_real_escape_string($con, $_SESSION['mentee_id']);

    // Get the current date and time
    $cancel_change_req_date = date("Y-m-d H:i:s");

    // Update the mentee table to cancel the change request and store the cancellation date
    $update_query = "UPDATE mentee SET change_req = 'NO', cancel_change_req_date = '$cancel_change_req_date' WHERE mentee_id = '$mentee_id'";
    $result = mysqli_query($con, $update_query);

    if($result) {
        // Success message (you can customize this based on your needs)
        $response = array('status' => 'success', 'message' => 'Change request cancelled successfully');
        echo json_encode($response);
    } else {
        // Error message
        $response = array('status' => 'error', 'message' => 'Failed to cancel change request');
        echo json_encode($response);
    }
} else {
    // If mentee_id is not provided in the POST request
    $response = array('status' => 'error', 'message' => 'Mentee ID not provided');
    echo json_encode($response);
}

// Close the database connection
mysqli_close($con);
?>
