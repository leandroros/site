function ts_allCheck() {
    	for(i=0;i<document.ts_adminform.elements.length;i++) {
      		if(document.ts_adminform.elements[i].type=="checkbox") {
                     if(document.ts_adminform.allbox.checked==true) {
				document.ts_adminform.elements[i].checked=true;
                     }
		     else {
				document.ts_adminform.elements[i].checked=false;
                     }
		}
        }
}
function ts_updateAllBox() {
        checkedBoxes=0;
        numOfBoxes=0;
    	for(i=0;i<document.ts_adminform.elements.length;i++) {
      		if(document.ts_adminform.elements[i].type=="checkbox" 
                   && document.ts_adminform.elements[i].name!="allbox") {
                	numOfBoxes++ ;
                	if (document.ts_adminform.elements[i].checked==true) { 
                		checkedBoxes++;
			}
                } 
        }
        if(numOfBoxes==checkedBoxes) {
		document.ts_adminform.allbox.checked=true;
        }
        else {
		document.ts_adminform.allbox.checked=false;
        }

}