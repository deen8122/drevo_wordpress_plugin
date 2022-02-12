function sel_show(id){
	document.getElementById(id).className = 'sel_items';
}
function sel_hide(id){
	document.getElementById(id).className = 'sel_items_h';
}
function sel_cur(ths,id){
	$('#'+id).val(ths.innerHTML);
	document.getElementById(id).focus();
	$(ths).parent().attr("class",'sel_items_h');
}
function sel_show_list(id, arr) {
	var value = document.getElementById(id).value;
	value = value.toLowerCase();

	var list = d.getElementById(id+'_items');
	var j=0;
	list.innerHTML = '';
	for(var i=0; i<arr.length; i++) {
		var val = arr [i];
		if(value!='')
		{			var srch = val.toLowerCase();
			if(srch.search(value)==0)
			{				list.innerHTML = list.innerHTML + '<a href="#'+id+'" onmousedown="sel_cur(this,\'' + id + '\')">' + val + '</a>';
				j++;			}		}
		else
		{
			list.innerHTML = list.innerHTML + '<a href="#'+id+'" onmousedown="sel_cur(this,\'' + id + '\')">' + val + '</a>';
			j++;
		}
	}
	if(j>1)	list.className = 'sel_items';
	else list.className = 'sel_items_h';
	return false;
}
function sel_show_list_all(id, arr) {
	var value = document.getElementById(id).value;
	var list = d.getElementById(id+'_items');
	if(value == '') list.className = 'sel_items';
	else
	{
		var j=0;
		list.innerHTML = '';
		for(var i=0; i<arr.length; i++) {			var val = arr [i];
			list.innerHTML = list.innerHTML + '<a href="#'+id+'" onmousedown="sel_cur(this,\'' + id + '\')">' + val + '</a>';
			j++;
		}
		if(j>1)	list.className = 'sel_items';
		else list.className = 'sel_items_h';
	}
	return false;
}
function sel_show_ajax(id, url, nov) {	val = document.getElementById(id).value;
	if(val != '')
	{
		xmlHttp.open("GET", '/2/'+url+val+'&new='+nov, true);
		xmlHttp.onreadystatechange = updateList;
		xmlHttp.send(null);
	}
	else
	{		document.getElementById(id).className = 'sel_items_h';	}
	return false;
}
function updateList() {
     if (xmlHttp.readyState == 4) {
       if (xmlHttp.status == 200) {
        var response = xmlHttp.responseText.split("|");
        var list = d.getElementById(response[0]+'_items');
        var i=0;
        list.innerHTML = '';
		for (var key in response) {			if(i!=0)
			{
	    		var val = response [key];
	    		list.innerHTML = list.innerHTML + '<a href="#" onmousedown="sel_cur(this,\'' + response[0] + '\')">' + val + '</a>';
			}
			i++;
		}
        if(i>1)
        {        	list.className = 'sel_items';
        }
        else list.className = 'sel_items_h';
       } else
         alert("status is " + request.status);
     }
}
