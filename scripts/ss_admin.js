
function modeChanged() {
    document.adminform.operation.value = document.adminform.showusers.value;
    document.adminform.submit();
}




function allCheck() {
    	for(i=0;i<document.adminform.elements.length;i++) {
      		if(document.adminform.elements[i].type=="checkbox") {
                     if(document.adminform.allbox.checked==true) {
				document.adminform.elements[i].checked=true;
                     }
		     else {
				document.adminform.elements[i].checked=false;
                     }
		}
        }
}





function updateAllBox() {
        checkedBoxes=0;
        numOfBoxes=0;
    	for(i=0;i<document.adminform.elements.length;i++) {
      		if(document.adminform.elements[i].type=="checkbox" 
                   && document.adminform.elements[i].name!="allbox") {
                	numOfBoxes++ ;
                	if (document.adminform.elements[i].checked==true) { 
                		checkedBoxes++;
			}
                } 
        }
        if(numOfBoxes==checkedBoxes) {
		document.adminform.allbox.checked=true;
        }
        else {
		document.adminform.allbox.checked=false;
        }

}




function changePage(op) {
	document.adminform.pageoperation.value = op;
	//document.adminform.submit();

}

function changeOp(op) {
        document.adminform.operation.value = op;
        //document.fh_adminform.submit();

}