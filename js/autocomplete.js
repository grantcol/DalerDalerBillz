//autocomplete.js

//Performs the query to request.php and sets the autocomplete list in html
function autoCompleteQuery() {
	var minLen = 0;
	var hintStr = $("#artist_search").val();
	var reqType = "search";
	if(hintStr.length >= minLen) {
		$.ajax({ 
			url : 'php/request.php',
			type : 'POST',
			data : { hintStr : hintStr,  reqType : reqType },
			success : function(data) {
				$('#artist_list').show();
				$('#artist_list').html(data);
			},
			error: function (jqXHR, textStatus, errorThrown) { console.log("REQUEST FAILED"); }
		});
	}
	else {
		$('#artist_list').hide();
	}
}

//Fills the input with the selected artist
function setSelect(item, id) {
	
	$('#artist_search').val(item);
	$('#artist_search').data("artistid", id);
	$('#artist_list').hide();
}

function spArtistQuery(artistId) {
	$.ajax({
		url : 'php/request',
		type : 'POST',
		data : { artistId : artistId },
		success : function(data) {
			console.log(data);
			//setup the 2nd page here with wordcloud etc.
			//should probably recieve a json encoded array
		},
		error: function (jqXHR, textStatus, errorThrown) { console.log("REQUEST FAILED"); }
	});
}

function generateWordCloud() {
	var hintStr = $("#artist_search").val();
	var artistId = $("#artist_search").data("artistid");
	console.log("AID "+artistId);
	$.ajax({
		url : 'php/request.php',
		type : 'POST',
		data : { hintStr : hintStr, reqType : 'track', artistId : artistId },
		success : function(data) {
			console.log(data);
			//setup the 2nd page here with wordcloud etc.
			//should probably recieve a json encoded array
			var cloud = data['cloud_string'];
			$("#cloud").html(cloud);
		},
		dataType : "json",
		error: function (jqXHR, textStatus, errorThrown) { console.log("REQUEST FAILED: "+textStatus+" "+errorThrown); console.log(jqXHR); }
	});
}