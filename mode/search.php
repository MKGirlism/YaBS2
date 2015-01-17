<?php
if (!empty($_POST)) {
	// MySQL
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	// Search
	include("module/postfunctions.php");
	
	$query = clear($_POST['query']);
	$appli = $_POST['app'];	
	
	if ($appli == "Pages") {
		$app = 'Pages';
		$sort = 'id';
		$titler = 'Title';
	}
	
	if ($appli == "Blog") {
		$app = 'Blogs';
		$sort = 'id';
		$titler = 'Name';
	}
	
	// Load Data
	if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
	$start_from = ($page-1) * 10;
	$sql = "SELECT `id`, `$titler`, `Content`, `Privacy` FROM `$app` WHERE `Content` LIKE '%$query%' ORDER BY '$sort' DESC LIMIT $start_from, 10";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($id, $title, $body, $priv);
	
	while ($stmt->fetch()) {
		if ($priv == 0 || $admin) {
			if ($appli == "Blog") echo "<div id='BlogTitle'><a href='?mode=post&id=$id'>$title</a></div><br />";
			else if ($appli == "Pages") echo "<div id='BlogTitle'><a href='?mode=page&id=$id'>$title</a></div><br />";
			echo "<div id='BlogData'>Something like this?</div>";
			
			if ($appli == "Blog") echo "<div id='BlogBody'>".ReadMore(getBBCode(getSmile($body)))."... (<a href='?mode=post&id=$id'>Read More</a>)</div><br /><br />";
			else if ($appli == "Pages") echo "<div id='BlogBody'>".ReadMore(getBBCode(getSmile($body)))."... (<a href='?mode=page&id=$id'>Read More</a>)</div><br /><br />";
		}
		//$body = mysql_result($result,$i,"$searchon");
	    //    echo "<div id=\"BlogBody\"><div id=\"BlogData\"><b>Something like this?</b></div><br />$body</div><br /><br />";
		//$i++;
	}
}

else {
	
}
?>
