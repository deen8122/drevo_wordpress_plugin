function selectAreaUpdate(p,s,path,id,sName){
	if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){
		preAreaName = p;
		selAreaName = s;
		XMLPath 	= path;
		selName 	= sName;
		parentID	= id;
		xmlHttp.open("GET", path + id, false);
		xmlHttp.send(null);
		// прога
	}
	else setTimeout('selectAreaUpdate("'+p+'","'+s+'","'+path+'",'+id+',"'+sName+'")', 10);
	
}

function handleServerResponse(){
	if(xmlHttp.readyState == 4){
		if(xmlHttp.status == 200){
			xmlRoot = xmlHttp.responseXML.documentElement;
			
			var hr = ' &raquo; ';

			preArea = document.getElementById(preAreaName);
			selArea = document.getElementById(selAreaName);			
			
			p_ids	= xmlRoot.getElementsByTagName('p_id');
			p_vals	= xmlRoot.getElementsByTagName('p_val');
			preArea.innerHTML = '';
			for(i=0;i<p_vals.length;i++){
				preArea.innerHTML += '<a href="javascript: selectAreaUpdate(\''+preAreaName+"','"+selAreaName+"','"+XMLPath+"',"+p_ids.item(i).firstChild.data + ",'"+selName+'\')">' + p_vals.item(i).firstChild.data + '</a>';
				if(i<p_vals.length-1) preArea.innerHTML += hr;
			}
						
			vals 	= xmlRoot.getElementsByTagName('str');
			if(vals.length>0){
				if(preArea.innerHTML != '') preArea.innerHTML += hr;
				ids = xmlRoot.getElementsByTagName('id');
				var selAreaText = '<select onChange="if(this.value==\'otherValue\') document.getElementById(\''+selAreaName+'\').innerHTML+=\'подробнее: <input type=`text` name=`'+selName+'_other`> \'; else selectAreaUpdate(\''+preAreaName+"','"+selAreaName+"','"+XMLPath+"'"+',this.value,'+"'"+selName+"'"+')"><option></option>';
				for(i=0;i<vals.length;i++){
					selAreaText += '<option value="'+ids.item(i).firstChild.data+'">'+vals.item(i).firstChild.data+'</option>';
				}
				selArea.innerHTML = selAreaText + '<option value="otherValue">другое</option></select>';
			} else selArea.innerHTML = ', если нужно, подробнее: <input type=`text` name=`'+selName+'_other`>';
			selArea.innerHTML += '<input type="hidden" name="' +selName+ '" value="' +parentID+ '">';
		}
		else{
			alert('Ошибка загрузки значений. Подождите немного.');
		}
	}
}
