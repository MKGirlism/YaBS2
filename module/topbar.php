<?php
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	$joke = "SELECT * FROM Jokes ORDER BY rand() LIMIT 1";
	
	$joker = $mysqli->prepare($joke);
	$joker->execute();
	
	$joker->bind_result($jid, $jtext);
	
	// Random Jokes.
	echo "<b>Random Joke: </b> ";
	while ($joker->fetch()) {
	        echo $jtext;
	}
	
	$joker->close();
	$mysqli->close();
?>
