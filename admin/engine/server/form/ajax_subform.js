var thissubform = false;

function show_subform(id_span,ifrsrc){
	if(thissubform!=id_span) close_subform();
	thissubform = id_span;
	
	var span = document.getElementById(id_span);
	
	var iframed = "<iframe src='"+ifrsrc+"&iframe=1' frameborder='0' style='background-color:white;";
	if(getNameBrowser()=="msie"){
		iframed += "' width='768px' height='512px'";
	} else {
		iframed += "width:100%;height:100%;'";
	}
	iframed += "></iframe>";
	
	span.innerHTML = "";
	span.innerHTML += "<div id='"+id_span+"_' onclick='close_subform_query(1)' style='position:absolute;right:0px;bottom:0px;width:1280px;height:1024px;background:black;z-index:100;'>";
	span.innerHTML += "<div id='"+id_span+"__' style='background-color:white;position:absolute;z-index:200;padding:0px;top:15%;left:15%;width:70%;height:70%;'>"+iframed+"</div>";
	span.innerHTML += "&nbsp;<div></div></div>";
	//
	
	if(getNameBrowser()!="opera"){
		setElementOpacity(id_span+"_",0.5); /**/
		setElementOpacity(id_span+"__",1); /**/
	}
	
	return false;
}

function save_subform(){
	for(i=0;i<subformarr.lenght;i++){
		alert(subformarr[i]);
	}
}

function subform_addval(good_id,text,urledit){
	var span = document.getElementById("_"+thissubform);
	span.innerHTML += "<div>"+text+" [<a href='#edit="+good_id+"' onclick='show_subform(\""+thissubform+"\",\""+urledit+"&action=edit&id="+good_id+"\")'>редактировать</a>]</div>";
	
	document.getElementById("a_"+thissubform).innerHTML = "";
}

function close_subform(){
	if(thissubform!=false) document.getElementById(thissubform).innerHTML = "";
	return false;
}
function close_subform_query($msgid){
	if($msgid==1) $msg = 'Закрыть форму без сохранения изменённых данных?';
	
	if( confirm($msg) ){
		close_subform();
	}
	return false;
}



function setElementOpacity(sElemId, nOpacity)
{
  var opacityProp = getOpacityProperty();
  var elem = document.getElementById(sElemId);

  if (!elem || !opacityProp) return; // Если не существует элемент с указанным id или браузер не поддерживает ни один из известных функции способов управления прозрачностью
  
  if (opacityProp=="filter")  // Internet Exploder 5.5+
  {
    nOpacity *= 100;
	
    // Если уже установлена прозрачность, то меняем её через коллекцию filters, иначе добавляем прозрачность через style.filter
    var oAlpha = elem.filters['DXImageTransform.Microsoft.alpha'] || elem.filters.alpha;
    if (oAlpha) oAlpha.opacity = nOpacity;
    else elem.style.filter += "progid:DXImageTransform.Microsoft.Alpha(opacity="+nOpacity+")"; // Для того чтобы не затереть другие фильтры используем "+="
  }
  else // Другие браузеры
    elem.style[opacityProp] = nOpacity;
}
function getOpacityProperty()
{
  if (typeof document.body.style.opacity == 'string') // CSS3 compliant (Moz 1.7+, Safari 1.2+, Opera 9)
    return 'opacity';
  else 
  if (typeof document.body.style.MozOpacity == 'string') // Mozilla 1.6 и младше, Firefox 0.8 
    return 'MozOpacity';
  else if (typeof document.body.style.KhtmlOpacity == 'string') // Konqueror 3.1, Safari 1.1
    return 'KhtmlOpacity';
  else if (document.body.filters && navigator.appVersion.match(/MSIE ([\d.]+);/)[1]>=5.5) // Internet Exploder 5.5+
    return 'filter';

  return false; //нет прозрачности
}

function occur(str, bi, bii) {
	var pos;
	pos = str.indexOf(bi);
	for (var count = 0; pos != -1; count++){
    pos = str.indexOf(bi, pos + bi.length);
	 str = str.replace(bi, bii)
	}
  return str;
}

function strhash(str){
	str = occur(str, "а", "*a*");
	str = occur(str, "б", "*b*");
	str = occur(str, "в", "*v*");
	str = occur(str, "г", "*g*");
	str = occur(str, "д", "*d*");
	str = occur(str, "ё", "*e*");
	str = occur(str, "е", "*e*");
	str = occur(str, "ж", "*gh*");
	str = occur(str, "з", "*z*");
	str = occur(str, "и", "*i*");
	str = occur(str, "й", "*y*");
	str = occur(str, "к", "*k*");
	str = occur(str, "л", "*l*");
	str = occur(str, "м", "*m*");
	str = occur(str, "н", "*n*");
	str = occur(str, "о", "*o*");
	str = occur(str, "п", "*p*");
	str = occur(str, "р", "*r*");
	str = occur(str, "с", "*s*");
	str = occur(str, "т", "*t*");
	str = occur(str, "у", "*u*");
	str = occur(str, "ф", "*f*");
	str = occur(str, "х", "*h*");
	str = occur(str, "ц", "*c*");
	str = occur(str, "ч", "*ch*");
	str = occur(str, "ш", "*sh*");
	str = occur(str, "щ", "*shc*");
	str = occur(str, "ь", "*mz*");
	str = occur(str, "ы", "*i*");
	str = occur(str, "ъ", "*tz*");
	str = occur(str, "э", "*e*");
	str = occur(str, "ю", "*yu*");
	str = occur(str, "я", "*ya*");
	str = occur(str, "А", "*A*");
	str = occur(str, "Б", "*B*");
	str = occur(str, "В", "*V*");
	str = occur(str, "Г", "*G*");
	str = occur(str, "Д", "*D*");
	str = occur(str, "Е", "*E*");
	str = occur(str, "Ё", "*E*");
	str = occur(str, "Ж", "*GH*");
	str = occur(str, "З", "*Z*");
	str = occur(str, "И", "*I*");
	str = occur(str, "Й", "*Y*");
	str = occur(str, "К", "*K*");
	str = occur(str, "Л", "*L*");
	str = occur(str, "М", "*M*");
	str = occur(str, "Н", "*N*");
	str = occur(str, "О", "*O*");
	str = occur(str, "П", "*P*");
	str = occur(str, "Р", "*R*");
	str = occur(str, "С", "*S*");
	str = occur(str, "Т", "*T*");
	str = occur(str, "У", "*U*");
	str = occur(str, "Ф", "*F*");
	str = occur(str, "Х", "*H*");
	str = occur(str, "Ц", "*C*");
	str = occur(str, "Ч", "*CH*");
	str = occur(str, "Ш", "*SH*");
	str = occur(str, "Щ", "*SHC*");
	str = occur(str, "Ь", "*MZ*");
	str = occur(str, "Ы", "*I*");
	str = occur(str, "Ъ", "*TZ*");
	str = occur(str, "Э", "*E*");
	str = occur(str, "Ю", "*YU*");
	str = occur(str, "Я", "*YA*");
	
	alert(str);
}


function getNameBrowser() {
  var ua = navigator.userAgent.toLowerCase();
  // Определим Internet Explorer
  if (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1) {
    return "msie"
  }
  // Opera
  if (ua.indexOf("opera") != -1) {
    return "opera"
  }
  // Gecko = Mozilla + Firefox + Netscape
  if (ua.indexOf("gecko") != -1) {
    return "gecko";
  }
  // Safari, используется в MAC OS
  if (ua.indexOf("safari") != -1) {
    return "safari";
  }
  // Konqueror, используется в UNIX-системах
  if (ua.indexOf("konqueror") != -1) {
    return "konqueror";
  }
  return "unknown";
} 

