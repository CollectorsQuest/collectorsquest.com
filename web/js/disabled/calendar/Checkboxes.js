function validateCheckArray(whichForm,whichCheckBoxArray,myMin){
	var _countChecked = 0;
	var dirty = 0;
	
	
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		if(document[whichForm][whichCheckBoxArray].checked == true){
			_countChecked++;
		}//end if
		
	} else {
		/* iterate through all the elements in the checkbox array */
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++)
		{
			/* and check to see if each is checked */
			if(document[whichForm][whichCheckBoxArray][i].checked===true)
				/* if it is, increment a counter */
				{ _countChecked++; }
		}
		
	}//end if
	
	
	/* is the count too low */
	if(_countChecked < myMin)
		{ 	dirty = 1;
			} else {
			dirty = 0;
			}//end if
	
	return dirty;
	
}//end howManyChecked()

function checkAllArray(whichForm, whichCheckBoxArray){
	for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++)
	{
		document[whichForm][whichCheckBoxArray][i].checked=true
	}//end for
}//end checkAllArray

function uncheckAllArray(whichForm, whichCheckBoxArray){
	for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++)
	{
		document[whichForm][whichCheckBoxArray][i].checked=false
	}//end for
}//end checkAllArray

function checkUpdateString(whichForm, whichCheckBoxArray){
	var checkedString = '';
	var cnt = 0;
		
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		if(document[whichForm][whichCheckBoxArray].checked == true){
			checkedString = document[whichForm][whichCheckBoxArray].value;
		}//end if
		
	} else {
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++)
		{
			if(document[whichForm][whichCheckBoxArray][i].checked==true){
				if(cnt > 0){checkedString = checkedString + ','};
				checkedString = checkedString + document[whichForm][whichCheckBoxArray][i].value;
				cnt++;
			}//end if
		}//end for
	}//end if	
	
	return checkedString;
	
}//end checkAllArray