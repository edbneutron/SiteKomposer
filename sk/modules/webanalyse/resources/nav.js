function ToggleDisplay(oButton, oItems) {
    oItems=GetObj(oItems);

     if (oItems.style.display == "none") {
          oItems.style.display = "block";
          oButton.src = "./resources/images/iconMoins.gif";
     }     else {
          oItems.style.display = "none";
          oButton.src = "./resources/images/iconPlus.gif";
     }
     return false;
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
      window.open(theURL,winName,features);
}

function onloadfct(closea)
{
    var url;
    url = window.opener.location;
    window.opener.location.href = url;
    
    if (closea == true)
        window.close();
}

function ChangeInput(str)
{
    if (document.all)
    {
        if (str.disabled == true)
            str.disabled = false;
        else
            str.disabled = true 
    }
    else if(document.getElementById)
    {
        alert("dd");
        alert (document.getElementById("ip").disabled); 
    }
    /*
    alert(str.disabled);
    if (str.disabled == true && document.all)
        str.disabled = false;
    else
        str.disabled = true 
    
    if (str.disabled == true && document.getElementById
        str.disabled = 'readonly';      
    else
        str.disabled = 'disabled';      
    
//  alert(str.disabled);
    alert(str.disabled);
    return str.disabled;
*/
}

function GetObj(str)
{
    if (document.all)
        return document.all(str);
    else if (document.getElementById)
        return document.getElementById(str);
    else
        return null;
}