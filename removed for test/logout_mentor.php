<?php

session_start();

if(isset($_SESSION['mentor_id']))
{
    unset($_SESSION['mentor_id']);
}

header("Location: index.php");
die;
?>