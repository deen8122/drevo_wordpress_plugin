function confirmnotsave(){
	if(document.getElementById('frm_sbmt').disabled==true){
		return true;
	} else {
		//alert("Данные не сохранены");
		return false;
	}
}
function enableSubmit(){
	document.getElementById('frm_sbmt').disabled = false;
}