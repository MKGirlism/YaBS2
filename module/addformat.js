// Texarea with buttons for format text and smiles - http://coursesweb.net/javascript/

// To add HTML tags instead of BbCode, replace "bbcode" with "html"
var tagtype = 'bbcode';

// Here add the hex code of the colors (without #)
var colors = [ 'bebebe', 'dadada', '0000da', '0101ff', 'ccccff', 'e8edfe', '00da00', '00fe00', 'ccffda', 'da0000', 'ff0000', 'ffcccc', 'eded00', 'fefe00', 'ffffda' ];

var id_txtfield = 'message';       // ID of the form field /textarea in which to add the tags /bbcode

// sets and add colors in #getcolor
function setColors() {
  var nr_colors = colors.length;
  var addcolors = '';       // stores the <span> with code for colors
  for(var i=0; i<nr_colors; i++) {
    if(i != 0 && (i % 5) == 0) addcolors += '<br/>';     // add nel row to each five colors
    addcolors += '<span title="#'+ colors[i]+ '" style="width:8px;height:8px;margin:2px;cursor:pointer;background-color:#'+ colors[i]+ ';" onclick="addTag(this, \'color\')">&nbsp; &nbsp;</span>';
  }
  document.getElementById('getcolor').innerHTML = addcolors;
  document.getElementById('getcolor').style.display = 'none';
}
setColors();        // calls the function to add the colors in webpage

// check and pass the URL
function setUrl() {
  var url = window.prompt('Add URL address (with http://...)', 'http://');    // open Prompt to add URL
  var regx_www = /^http:\/\/[a-zA-z0-9_-]+[.]+/;              // RegExp for URL

  // check if correct URL (with http://), send it to addTag(), else alert
  if (url.match(regx_www)) addTag(url, 'url');
  else alert('Incorrect URL address');
}

// determine the coords of select in textarea (zon), and the selected text, for IE
function cursorPosition(star, en, zon){
var textarea = document.getElementById(zon);
textarea.focus();

var selection_range = document.selection.createRange().duplicate();

if (selection_range.parentElement() == textarea) { // Check that the selection is actually in our textarea
// Create three ranges, one containing all the text before the selection,
// one containing all the text in the selection (this already exists), and one containing all
// the text after the selection.
var before_range = document.body.createTextRange();
before_range.moveToElementText(textarea); // Selects all the text
before_range.setEndPoint("EndToStart", selection_range); // Moves the end where we need it

var after_range = document.body.createTextRange();
after_range.moveToElementText(textarea); // Selects all the text
after_range.setEndPoint("StartToEnd", selection_range); // Moves the start where we need it

var before_finished = false, selection_finished = false, after_finished = false;
var before_text, untrimmed_before_text, selection_text, untrimmed_selection_text, after_text, untrimmed_after_text;

// Load the text values we need to compare
before_text = untrimmed_before_text = before_range.text;
selection_text = untrimmed_selection_text = selection_range.text;
after_text = untrimmed_after_text = after_range.text;

// Check each range for trimmed newlines by shrinking the range by 1 character and seeing
// if the text property has changed. If it has not changed then we know that IE has trimmed
// a \r\n from the end.
do {
if (!before_finished) {
if (before_range.compareEndPoints("StartToEnd", before_range) == 0) {
before_finished = true;
} else {
before_range.moveEnd("character", -1)
if (before_range.text == before_text) {
untrimmed_before_text += "\r\n";
} else {
before_finished = true;
}
}
}
if (!selection_finished) {
if (selection_range.compareEndPoints("StartToEnd", selection_range) == 0) {
selection_finished = true;
} else {
selection_range.moveEnd("character", -1)
if (selection_range.text == selection_text) {
untrimmed_selection_text += "\r\n";
} else {
selection_finished = true;
}
}
}
if (!after_finished) {
if (after_range.compareEndPoints("StartToEnd", after_range) == 0) {
after_finished = true;
} else {
after_range.moveEnd("character", -1)
if (after_range.text == after_text) {
untrimmed_after_text += "\r\n";
} else {
after_finished = true;
}
}
}

} while ((!before_finished || !selection_finished || !after_finished));

// ** END Untrimmed success test

// Define into an array the start, and end of selected text, and the final text
var re = new Array();
re['startPos'] = untrimmed_before_text.length;
re['endPos'] = re['startPos'] + untrimmed_selection_text.length;
re['final_text'] = untrimmed_before_text +star+ untrimmed_selection_text +en+ untrimmed_after_text;

  return re;
}
}

// position the cursor in the element with ID of "zona" to Xpos coord
function set_xpos(zona, Xpos) {
  var txtarea = document.getElementById(zona);
  if(txtarea != null) {
  if(txtarea.createTextRange) {
    var range = txtarea.createTextRange();
    range.move('character', Xpos);
    range.select();
  }
  else {
    if(txtarea.selectionStart) {
    txtarea.focus();
      txtarea.setSelectionRange(Xpos, Xpos);
    }
    else {
      txtarea.focus();
    }
    }
  }
}

// Add tags: B, I, U, and URL in form (for IE uses cursorPosition(), and set_xpos)
function addTag(btn, type) {
  var tgop = (tagtype == 'html') ? '<' : '[';     // character opening tag
  var tgen = (tagtype == 'html') ? '>' : ']';     // character ending tag

  if(type) {
    // For URL and Colors
    if(tagtype == 'html') {
      var start = (type == 'url') ? '<a target="_blank" href="'+btn+'">' : '<span style="color:'+btn.title+'">';
      var end = (type == 'url') ?  '</a>' : '</span>';
    }
    else {
      var start = (type == 'url') ? '[url='+btn+']' : '[color='+btn.title+']';
      var end = '[/'+type+']';
    }
  }
  else {
    var start = tgop+btn.title+tgen;
    var end = tgop+'/'+btn.title+tgen;
  }
  var txtarea = document.getElementById(id_txtfield);
  if (txtarea.selectionStart || txtarea.selectionStart==0) { // Mozilla, Opera
    // Define into an array the start, and end of selected text, and the final text in textarea
    var rezult = new Array();
    rezult['startPos'] = txtarea.selectionStart;
    rezult['endPos'] = txtarea.selectionEnd;
    rezult['final_text'] = txtarea.value.substring(0, rezult['startPos']) + start + txtarea.value.substring(rezult['startPos'], rezult['endPos']) + end + txtarea.value.substring(rezult['endPos'], txtarea.value.length);
  }
  else if (document.selection) {   // IE
    var rezult = cursorPosition(start, end, id_txtfield);
 }

  // Add the new text in textarea, calls set_xpos() to position cursor to Xpos
  txtarea.value = rezult['final_text'];
  var Xpos = rezult['endPos']+start.length;
  set_xpos(id_txtfield, Xpos);
}

// Add code for clicked smile in element with ID passed in "id_txtfield"
function addSmile(smile) {
  // object with characters that represent the smiles, and the name of the GIF file for each one
  var smchr = {':)':0, ':(':1, ':P':2, ':D':3, ':S':4, ':O':5, ':=)':6, ':|H':7, ':X':8, ':-*':9}

  var txtarea = document.getElementById(id_txtfield);
  txtarea.value += (tagtype == 'html') ? '<img src="icos/'+smchr[smile.title.toUpperCase()]+'.gif" />' : smile.title;
  txtarea.focus();
}
