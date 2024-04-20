<?php
session_start();
include("connection.php");

function deleteAccount($table, $id) {
    global $con;

    // Check if the user is logged in
    if (!isset($_SESSION[$table.'_id'])) {
        header("Location: login.php");
        exit();
    }

    // Delete account data from the specified table
    $query = "DELETE FROM $table WHERE {$table}_id = '$id'";
    mysqli_query($con, $query);

    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: index.php");
    exit();
}

// Example usage for mentee deletion
deleteAccount("mentee", $_SESSION['mentee_id']);

// Example usage for mentor deletion
deleteAccount("mentor", $_SESSION['mentor_id']);
?>
