function addVarToTmpl(elem) {
    var myQuery = document.getElementById('goodnewtemplate_text');

	chaineAj = elem.innerHTML;

	//IE support
	if (document.selection) {
		myQuery.focus();
		sel = document.selection.createRange();
		sel.text = chaineAj;
		document.sqlform.insert.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (myQuery.selectionStart || myQuery.selectionStart == "0") {
		var startPos = myQuery.selectionStart;
		var endPos = myQuery.selectionEnd;
		var chaineSql = myQuery.value;

		myQuery.value = chaineSql.substring(0, startPos) + chaineAj + chaineSql.substring(endPos, chaineSql.length);
	} else {
		myQuery.value += chaineAj;
	}
    
}