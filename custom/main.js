function direct_user()
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

function xml_parse(vad)
{
	var randomVer = Math.floor((Math.random()*100)+1);
	$.ajax({
	type: "GET",
	url: "../common/datan.xml?ver="+randomVer,
	success: function (xml)
		{
			//find every Tutorial and print the author
			$("#xmlrespons").empty();
			$(xml).find(vad).each(function()
			{	
				
				$("#xmlrespons").append($(this).text() + "<br />");
			});
		},
	error: function(er){
        $("#xmlrespons").html('<p>error</p>');
            alert(er);
	    }
	});
};

