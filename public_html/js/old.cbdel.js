$(function() {
  
  $(".cbdel").click(function() {  
	var cbid = $(this).attr("cbid");
  	var cbdel = confirm("Are you sure you want to delete record "+cbid+" from the system?");
	if (cbdel){
  		  		
  		var dataString = '?cdel='+cbid;
  		//alert (dataString); return false;
  		window.location.href = dataString;
  		 
/*		$.ajax({
   	
   	type: "GET",
   	url: "Chargebacks.php",
   	data: dataString,
   	success: function() {
   		//$('#'+cbid).html("");
   		//$('#alert').html("<div id='alert' class='box box-success closeable'>Chargeback <strong><em>"+cbid+"</em></strong> has been successfully removed from the system.</div>");
   		alert("Record deleted");
   	}   	
   
   }); */
  		
 	}
  	return false;
  });
  
  $("#cbadd").click(function() {  
	//var cbid = $(this).attr("cbid");
  	var cbdel = confirm("Are you sure you want to do  this?");
	if (cbdel){
  		  		
  		//var dataString = '?cdel='+cbid;
  		//alert (dataString); return false;
  		window.location.href = dataString;
  		 
/*		$.ajax({
   	
   	type: "GET",
   	url: "Chargebacks.php",
   	data: dataString,
   	success: function() {
   		//$('#'+cbid).html("");
   		//$('#alert').html("<div id='alert' class='box box-success closeable'>Chargeback <strong><em>"+cbid+"</em></strong> has been successfully removed from the system.</div>");
   		alert("Record deleted");
   	}   	
   
   }); */
  		
 	}
  	return false;
  });
  
  
});  