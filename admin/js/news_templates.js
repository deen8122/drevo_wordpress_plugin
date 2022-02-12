function addVarToTmpl(elem) {
	
	var myQuery = document.getElementById('goodnewtemplate_text');
	if(typeof focused!='undefined')
	{
	 	if(focused=="goodnewtemplate_title_0")
        	{
		var myQuery = document.getElementById('goodnewtemplate_title_0');	
	 	}
		if(focused=="")
        	{
		var myQuery = document.getElementById('goodnewtemplate_text');	
		}
	}
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
		focused="";
}
function addNVar(text) {
	
    txtarea = document.getElementById('goodnewtemplate_text');

    if(typeof focused!='undefined')
    {    
	if(focused=="goodnewtemplate_title_0")
        {
	var txtarea = document.getElementById('goodnewtemplate_title_0');
        }else{
        var txtarea = document.getElementById('goodnewtemplate_text');
        }
    }   
        text = ' ' + text + ' ';
        if (txtarea.createTextRange && txtarea.caretPos) {
                var caretPos = txtarea.caretPos;
                caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
                txtarea.focus();
		
        } else {
                txtarea.value  += text;
                txtarea.focus();
		
        }
	focused="";
}