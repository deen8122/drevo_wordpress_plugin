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
	if(!obj) alert('��� ������� �� ������ ��������� �������� � ������ ���������. ���������� � ������ ���������, ���� ��������� � ���������������.'); else return obj;
}