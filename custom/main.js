function direct_user() //Denna är inaktuell och används för närvarande inte
{
	if (screen.width <= 999) 
	{
		document.location = "../m/mobile.php";
	}
	else if (screen.width >= 1000) 
	{
		document.location = "../main/web.php";
	}
}

$(function() {
	
	$( "#button" ).button();

});


function getXMLHttp() {
	var xmlhttp;
	if (window.ActiveXObject) {
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} 
	else if (window.XMLHttpRequest) 
	{
	        xmlhttp = new XMLHttpRequest();
	} 
	else 
	{
	        alert("Finns inget stöd för Ajax!");
	}
	return xmlhttp;
}

function getPage(url,initialize) {
	if (initialize=="nej")
	{
		$("#maincontent").fadeOut("fast", function()
			{
				xmlhttp1=getXMLHttp();
				xmlhttp1.open("GET",url,true); //+"?ver="+randomVer
				xmlhttp1.setRequestHeader("If-Modified-Since", "Fri, 31 Dec 1999 23:59:59 GMT");
				xmlhttp1.onreadystatechange = updateInfo;
				xmlhttp1.send(null);
			});
	}
	else if (initialize=="ja")
	{
		xmlhttp1=getXMLHttp();
		xmlhttp1.open("GET",url,true); //+"?ver="+randomVer
		xmlhttp1.setRequestHeader("If-Modified-Since", "Fri, 31 Dec 1999 23:59:59 GMT");
		xmlhttp1.onreadystatechange = updateInfo;
		xmlhttp1.send(null);
	}
	 
}

function updateInfo() {
        if (xmlhttp1.readyState == 4) {
              var response = xmlhttp1.responseText;
              $("#maincontent").html(response).fadeIn("fast");
        }
}

