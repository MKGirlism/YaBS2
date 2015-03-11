<?php
	require("common.php");
	$powza = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8');
	
	if($powza <= 0) {
		header('Location: index.php');
        exit();
	}
	
	$query = "SELECT * FROM $userb";
	
	try {
		$stmt = $db->prepare($query);
		$stmt->execute();
	}
	catch(PDOException $ex) {
		die("Failed to run query: " . $ex->getMessage());
	}
	
	$rows = $stmt->fetchAll();
?>

<h1>Memberlist</h1>
<table border="1" cellpadding="4" width="100%">
	<tr>
		<th bgcolor=#000>Avatar</th>
		<th bgcolor=#000>Username</th>
		<th bgcolor=#000>Email</th>
		<th bgcolor=#000>Delete</th>
	</tr>
	<?php foreach($rows as $row): ?>
	<tr>
		<td bgcolor=#FFF><img src="<?php echo htmlentities($row['avatar'], ENT_QUOTES, 'UTF-8'); ?>" width="20px" /></td>
		<td bgcolor=#FFF><?php
		echo "<a href='?mode=profile&uid=".htmlentities($row['id'], ENT_QUOTES, 'UTF-8')."'>";
		echo "<span style=color:#800080>";
		echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8');
		echo "</span></a>"; ?></td>
		<td bgcolor=#FFF><?php echo "<span style='color:blue'>".htmlentities($row['email'], ENT_QUOTES, 'UTF-8')."</span>"; ?></td>
		<td bgcolor=#FFF><?php echo "<a href='?mode=userdel&uid=".htmlentities($row['id'], ENT_QUOTES, 'UTF-8')."'>Delete</a>"; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
