function direct_user()
{
	if (screen.width <= 999) 
	{
		document.location = "m/mobile.php";
	}
	else if (screen.width >= 1000) 
	{
		document.location = "web.php";
	}
}

$(function() {
	
	$( "#button" ).button();

});

function xml_parse(vad)
{
	$.ajax({
	type: "GET",
	url: "datan.xml",
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

