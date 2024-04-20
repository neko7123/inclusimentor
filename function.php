<?php

function check_login($con)
{
    if(isset($_SESSION['mentee_id']))
    {
        $id = $_SESSION['mentee_id'];
        $query = "SELECT * FROM mentee WHERE mentee_id = $id LIMIT 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $mentee_data = mysqli_fetch_assoc($result);
            return $mentee_data;           
        }
    }
    elseif(isset($_SESSION['mentor_id']))
    {
        $id = $_SESSION['mentor_id'];
        $query = "SELECT * FROM mentor WHERE mentor_id = $id LIMIT 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $mentor_data = mysqli_fetch_assoc($result);
            return $mentor_data;           
        }
    }

    // Redirect to login
    header("location: login.php");
    die;
}  

function random_num($length, $con, $id_type)
{
    // Ensure $length is at least 5
    if ($length < 5) {
        $length = 5;
    }

    // Set the maximum length to 5 digits
    $max_length = min($length, 5);

    do {
        $generated_id = "";
        for ($i = 0; $i < $max_length; $i++) {
            $generated_id .= rand(0, 9);
        }

        // Check if the generated number already exists in the database for the specified ID type
        $query = "SELECT {$id_type}_id FROM $id_type WHERE {$id_type}_id = '$generated_id'";
        $result = mysqli_query($con, $query);
    } while ($result && mysqli_num_rows($result) > 0);

    return $generated_id;
}


function findAndStoreMatches($con) {
    // Fetch unmatched mentees
    $query_mentees = "SELECT * FROM mentee WHERE matched_mentor = 0";
    $result_mentees = mysqli_query($con, $query_mentees);

    if ($result_mentees && mysqli_num_rows($result_mentees) > 0) {
        while ($mentee_data = mysqli_fetch_assoc($result_mentees)) {
            $matching_field = $mentee_data['mentorship_field'];

            // Find and store a new mentor for the mentee
            findAndStoreNewMentor($con, $mentee_data['mentee_id'], $matching_field);
        }
    }
}

function findAndStoreNewMentor($con, $mentee_id, $matching_field) {
    // Retrieve a list of unmatched mentors with the same matching field
    $query = "SELECT mentor_id FROM mentor WHERE matched_mentee = 0 AND work_field = '$matching_field'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the first available mentor
        $row = mysqli_fetch_assoc($result);
        $mentor_id = $row['mentor_id'];

        // Update the mentee's matched_mentor field
        $update_query_mentee = "UPDATE mentee SET matched_mentor = '$mentor_id' WHERE mentee_id = '$mentee_id'";
        mysqli_query($con, $update_query_mentee);

        // Update the mentor's matched_mentee field
        $update_query_mentor = "UPDATE mentor SET matched_mentee = '$mentee_id' WHERE mentor_id = '$mentor_id'";
        mysqli_query($con, $update_query_mentor);
    }
}

findAndStoreMatches($con);

function moveMentorToHistory($con, $mentor_id) {
    // Move the mentor to matched_history column in mentor table
    $update_query = "UPDATE mentor SET matched_history = '$mentor_id' WHERE mentor_id = '$mentor_id'";
    mysqli_query($con, $update_query);
}

function getMatchedMentee($con, $mentor_id) {
    // Your implementation to retrieve the matched mentee's ID based on the mentor's ID

    $query = "SELECT matched_mentee FROM mentor WHERE mentor_id = '$mentor_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $matched_mentee_id = $row['matched_mentee'];
        return $matched_mentee_id;
    } else {
        // Return false or some indication that no match was found
        return false;
    }
}

function getMatchedMentor($con, $mentee_id) {
    $query = "SELECT matched_mentor FROM mentee WHERE mentee_id = '$mentee_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['matched_mentor'];
    } else {
        return false;
    }
}

function sendChangeRequest($mentee_id) {
    global $con;

    $update_query = "UPDATE mentee SET change_req = 'YES' WHERE mentee_id = '$mentee_id'";
    
    // Execute the query
    mysqli_query($con, $update_query);
}

?>

