function bbButton(tag) {
   var Field = document.getElementById('message');
   var val = Field.value;
   var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
   var before_txt = val.substring(0, Field.selectionStart);
   var after_txt = val.substring(Field.selectionEnd, val.length);
   Field.value += '[' + tag + ']' + '[/' + tag + ']';
}

function bbButton2(tag) {
   var Field = document.getElementById('message');
   var val = Field.value;
   var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
   var before_txt = val.substring(0, Field.selectionStart);
   var after_txt = val.substring(Field.selectionEnd, val.length);
   Field.value += '[' + tag + '=#]' + '[/' + tag + ']';
}

function smileyFace(tag) {
   var Field = document.getElementById('message');
   var val = Field.value;
   var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
   var before_txt = val.substring(0, Field.selectionStart);
   var after_txt = val.substring(Field.selectionEnd, val.length);
   Field.value += ' ' + tag + ' ';
}
