<?php
	// MySQL
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$img_dir = "assets/images/";
        $image = $img_dir . basename($_FILES["Image"]["name"]);
        $uploadOK = 1;
        $imageFileType = pathinfo($image, PATHINFO_EXTENSION);
	$userid = $_SESSION['uname']['uid'];
	$insert = "INSERT INTO Images (id, uid, URL) VALUES (NULL, $userid, '$image')";
	
        if (isset($_POST["submit"])) {
                if (file_exists($image)) {
                        echo "File already exists.<br />";
                        $uploadOK = 0;
                }
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo "Only JPG, PNG, JPEG, and GIF, please!<br />";
                        $uploadOK = 0;
                }
                else {
                        $uploadOK = 1;
                }
                
                if ($uploadOK == 0) {
                        echo "File not Uploaded.<br />";
                }
                else {
			if (move_uploaded_file($_FILES["Image"]["tmp_name"], $image)) {
				if ($mysqli->query($insert)) {
                        	        echo "File ". basename($_FILES["Image"]["name"]) ." was Uploaded.<br />";
				}
	                        else {
					echo "Can't process, File Name contains unrecognised Characters.<br />";
				}
			}
			else {
				echo "Can't Upload. Did the Owner set the entire \"assets\" Folder to chmod 777 already?<br />";
			}
                }
        }
	
	// Load Data
	if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
	$start_from = ($page-1) * 5;
	$sql = "SELECT i.id, i.uid, i.url, u.uid, u.uname FROM Images AS i, Users AS u WHERE i.uid = u.uid ORDER BY i.id DESC LIMIT $start_from, 5";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($id, $uidI, $url, $uidU, $user);
	
	echo "<table border='0' cellpadding='2' width='100%'>";
	while ($stmt->fetch()) {
		echo "<td><a href='$url'><img src='$url' alt='$user\'s Submission' width=100 height=100 /></a><br />By <a href='?mode=profile&uid=$uidU'>$user</a></td>";
	}
	echo "</table>";
	
	$stmt->close();
	
	echo "<br />";
	$row = mysqli_fetch_row(mysqli_query($mysqli,"SELECT COUNT(URL) FROM Images"));
	$total_records = $row[0];
	$total_pages = ceil($total_records / 5);
	for ($i = 1; $i <= $total_pages; $i++)
		echo "<a href='?mode=uploader&page=".$i."'>".$i." </a>"; 
	
	if (!empty($_SESSION['uname'])) {
?>
<h1>Upload</h1><br />
<form action="?mode=uploader" method="post" enctype="multipart/form-data">
	<input type="file" name="Image" id="Image" /><br />
	<input type="submit" name="submit" value="Upload" />
</form>
<?php
	}
	else {
		echo "<br /><br />Login to Upload something.";
	}
	$mysqli->close();
?>
