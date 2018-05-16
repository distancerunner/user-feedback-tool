jQuery(document).ready( function($) {
	
	$(".helperform_submit_uft").click( function(){
		var name = document.getElementById("name_uft").value;
		var url = document.getElementById("url_uft").value;
		var comment = document.getElementById("comment_uft").value;
		var kind_of_message = document.getElementById("kind_of_message").value;
		var prio_of_message = document.getElementById("prio_of_message").value;
		// Returns successful data submission message when the entered information is stored in database.
		var dataString = 'name=' + name + '&url=' + url + '&comment=' + comment+ '&kind_of_message=' + kind_of_message+ '&prio_of_message=' + prio_of_message;
		if (name == '' || url == '') {
		alert("Es muss ein Name angeben werden.");
		} else {
		// AJAX code to submit form.
		$.ajax({
		type: "POST",
		url: locationvar_uft.pluginurl+"ajax/ajax_uft_js.php",
		data: dataString,
		cache: false,
		success: function() {
		//alert(html);
			$(".helperform_submit_uft").fadeOut(function() {
				$(".success_span_uft").fadeIn();
			});
		
		}

		});
		}
		return false;
	});

	//Close the Feedback Form
	$(".close_btn_uft").click( function(){
		$(".help-form-uft").fadeOut(function() {
			$("#comment_uft").val('');
		});
		
	})
	;
	//Open the Feedback Form and Reset the Submit Button
	$(".user_feedback_button_uft").click( function(){
		$(".help-form-uft").fadeIn(function() {
			
			$(".helperform_submit_uft").fadeIn(function() {
				$(".success_span_uft").fadeOut();
			});
		});
		
	});

	$("textarea").change( function(){
		$(".data_safed_uft").fadeOut(200);
	});

	$("select").change( function(){
		$(".data_safed_uft").fadeOut(200);
	});

	$("#SubmitDataBtn_uft").click( function(){
		var uid = document.getElementById("uid").value;
		var field005 = document.getElementById("field005").value;
		var field006 = document.getElementById("field006").value;
		var field007 = document.getElementById("field007").value;
		var field008 = document.getElementById("field008").value;

		// Returns successful data submission message when the entered information is stored in database.
		var dataString = 'field008=' + field008 + '&field005=' + field005 + '&field006=' + field006+ '&field007=' + field007+ '&uid=' + uid;
		if (dataString == '') {
		alert("Datenfehler beim senden.");
		} else {
		// AJAX code to submit form.
		$.ajax({
		type: "POST",
		url: locationvar_uft.pluginurl+"ajax/ajax_uft_js_admin.php",
		data: dataString,
		cache: false,
		success: function() {
			$(".data_safed_uft").fadeIn(150);
		}

		});
		}
		return false;
	});

	$("#DeleteDataBtn_uft").click( function(){
		$("#DeleteDataBtn_uft").fadeOut(50); 
		$("#OkDeleteDataBtn_uft").fadeIn(50);
	});

	$("#OkDeleteDataBtn_uft").click( function(){
		var uid = document.getElementById("uid").value;
			// Returns successful data submission message when the entered information is stored in database.
		var dataString = 'uid=' + uid+'&delete=true';

		if (dataString == '') {
		alert("Datenfehler beim senden.");
		} else {
		// AJAX code to submit form.
		$.ajax({
		type: "POST",
		url: locationvar_uft.pluginurl+"ajax/ajax_uft_js_admin.php",
		data: dataString,
		cache: false,
		success: function() {
			$("#SubmitDataBtn_uft").fadeOut(50); 
			$("#OkDeleteDataBtn_uft").fadeOut(50); 
			$(".data_deleted_uft").fadeIn(150);
		}

		});
		}
		return false;
	});
	
});
