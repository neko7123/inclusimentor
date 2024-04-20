<?php

session_start();

if(isset($_SESSION['mentee_id']))
{
    unset($_SESSION['mentee_id']);
}
elseif(isset($_SESSION['mentor_id']))
{
    unset($_SESSION['mentor_id']);
}

header("Location: login.php");
die;
?>
