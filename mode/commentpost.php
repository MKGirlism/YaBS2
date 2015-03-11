<?php
// Get ID of the Page.
ob_start();
$id = (int) $_GET['id'];
if ($id < 1)
{
        header('Location: index.php');
        exit();
}
ob_end_clean();
session_start();
?>
<?php

// Allow Slashes, and allow Enters.
include("module/postfunctions.php");

// Execute, as soon as the Posting starts.
if ($_POST['submit']) {
echo "1";
        // MySQL
	$con = mysqli_connect($hosty, $uname, $paswd, $dbnme);
        
	// Message Markup
       	$message = clear($_POST['message']);
        $message2 = nl2br($message);
        
	// Better Username, and Date
	$uid = $_SESSION['username']['id'];
        $date = mktime();
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	
	// Put it in the Database, and Inform the User, about it.
	$query = "INSERT INTO blg_comments (id, post_id, message, user_id, post_date, ip_address) VALUES (NULL, $id, '$message2', $uid, $date, '$ipaddress')";
	
	mysqli_query($con, $query);
	echo "Comment Posted.<br /><a href='?mode=post&id=".$id."'>View Comment</a>";
}

?>
