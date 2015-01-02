<?php
function getSmile($text) {
	$smile = array(
		':)' => 'smile.png',
		';)' => 'wink.png',
		':D' => 'biggrin.png',
		'o_o' => 'blank.png',
		':awsum:' => 'awsum.png',
		'-_-' => 'annoyed.png',
		'o_O' => 'bigeyes.png',
		':LOL:' => 'lol.png',
		':O' => 'jawdrop.png',
		':(' => 'frown.png',
		';_;' => 'cry.png',
		'>:' => 'mad.png',
		'O_O' => 'eek.png',
		'8-)' => 'glasses.png',
		'^_^' => 'cute.png',
		'^^;;;' => 'cute2.png',
		'>_<' => 'yuck.png',
		'<_<' => 'shiftleft.png',
		'>_>' => 'shiftright.png',
		'@_@' => 'dizzy.png',
		'^~^' => 'angel.png',
		'>:)' => 'evil.png',
		'x_x' => 'sick.png',
		':P' => 'tongue.png',
		':S' => 'wobbly.png',
		':[' => 'vamp.png',
		'~:o' => 'baby.png',
		':YES:' => 'yes.png',
		':NO:' => 'no.png',
		'<3' => 'heart.png',
		':3' => 'colonthree.png',
		':up:' => 'approve.png',
		':down:' => 'deny.png',
		':durr:' => 'durrr.png',
		'^^;' => 'embarras.png',
		':barf:' => 'barf.png',
		'._.' => 'ashamed.png',
		'\'.\'' => 'umm.png',
		'\'_\'' => 'downcast.png',
		':big:' => 'twwth.png',
		':lawl:' => 'lawl.png',
		':ninja:' => 'ninja.png',
		':pirate:' => 'pirate.png',
                ':XD:' => 'xd.png',
		'D:' => 'outrage.png',
		':sob:' => 'sob.png',
		':yum:' => 'yum.png'
	);
	
	foreach($smile as $smiley => $ascii) {
		$text = str_replace(
			$smiley,
			"<img src='assets/smileys/{$ascii}' />",
			$text
		);
	}
	
	return $text;
}

function getBBCode($text) {
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[s\](.*?)\[/s\]~s',
		'~\[o\](.*?)\[/o\]~s',
		'~\[centre\](.*?)\[/centre\]~s',
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png))\[/img\]~s',
		'~\[yt\](.*?)\[/yt\]~s',
		'~\[link=((?:ftp|https?)://.*?)\](.*?)\[/link\]~s',
		'~\[sound\](https?://.*?\.(?:mp3))\[/sound\]~s',
		'~\[size=(.*?)\](.*?)\[/size\]~s',
		'~\[colour=(.*?)\](.*?)\[/colour\]~s',
	);
	
	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<span style="text-decoration:underline;">$1</span>',
		'<span style="text-decoration:line-through;">$1</span>',
		'<span style="text-decoration:overline;">$1</span>',
		'<span align="center">$1</span>',
		'<img src="$1" alt="" />',
		'<iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
		'<a href="$1">$2</a>',
		'<audio controls><source src="$1" type="audio/mpeg">Your browser does not support the audio tag.</audio>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
	);
	
	return preg_replace($find, $replace, $text);
}

function clear($message) {
        if(!get_magic_quotes_gpc())
                $message = addslashes($message);
        $message = strip_tags($message);
        $message = htmlentities($message);
	$message = nl2br($message);
        return trim($message);
}

function GetAll($message) {
	return getSmile(getBBCode(strip_tags($message)));
}

function ReadMore($content, $word_limit = 20) {
        $words = explode(" ", $content);

        return implode(" ", array_splice($words, 0, $word_limit));
}
?>
