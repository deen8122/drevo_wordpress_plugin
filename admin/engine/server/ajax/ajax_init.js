var xmlHttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject(){
	var obj;
	if(window.ActiveXObject){
		try{obj = new ActiveXObject("Microsoft.XMLHTTP");}
		catch(e){obj = false;}
	}
	else{
		try{obj = new XMLHttpRequest();}
		catch(e){obj = false;}
	}
	if(!obj) alert('Ваш браузер не сможет корректно работать с данной страницей. Обратитесь в службу поддержки, либо свяжитесь с администратором.'); else return obj;
}