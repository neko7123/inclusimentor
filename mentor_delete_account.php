<?php
session_start();
include("connection.php");

// Function to delete mentor account
function deleteMentorAccount($con, $mentor_id) {
    // Delete mentor account data from the database
    $query = "DELETE FROM mentor WHERE mentor_id = '$mentor_id'";
    mysqli_query($con, $query);

    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if the mentor is logged in
if (!isset($_SESSION['mentor_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the mentor ID from the session
$mentor_id = $_SESSION['mentor_id'];

// Call the function to delete the mentor account
deleteMentorAccount($con, $mentor_id);
?>

