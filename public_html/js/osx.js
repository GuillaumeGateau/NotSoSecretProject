jQuery(function ($) {
	var OSX = {
		container: null,
		init: function () {
			$("input.osx, a.osx").click(function (e) {
				e.preventDefault();	
				
				var key = $(this).attr('key');
				var fval = $(this).attr('fval');
				var note = $('.note_img.'+key).attr('note');
				
				$("#nDate").val(fval);
				$("#nNote").val(note);
				$("#nKey").val(key);
				
				$("#osx-modal-content").modal({
					overlayId: 'osx-overlay',
					containerId: 'osx-container',
					closeHTML: null,
					minheight: 120,
					height: 300,
					opacity: 65, 
					position: ['0',],
					overlayClose: false,
					onOpen: OSX.open,
					onClose: OSX.close
				});
			});
		},
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('fast', function () {
				$("#osx-modal-content", self.container).show();
				var title = $("#osx-modal-title", self.container);
				title.show();
				d.container.slideDown('fast', function () {
					setTimeout(function () {
						var h = $("#osx-modal-data", self.container).height()
							+ title.height()
							+ 20; // padding
						d.container.animate(
							{height: h}, 
							20,
							function () {
								$("div.close", self.container).show();
								$("#osx-modal-data", self.container).show();
							}
						);
					}, 0);
				});
			})
		},
		close: function (d) {
			var self = this; // this = SimpleModal object
			d.container.animate(
				{top:"-" + (d.container.height() + 20)},
				200,
				function () {
					self.close(); // or $.modal.close();
				}
			);
		}
	};

	OSX.init();
	
   
$(".submit_note").click(function() {  
         
   var key = $("input#nKey").val();
   var note = $("textarea#nNote").val();
   
   var dataString = 'nKey='+key+'&nNote='+note;
   
   //alert (dataString); /return false;
   
   $.ajax({
   	
   	type: "POST",
   	url: "Notes",
   	data: dataString,
   	success: function(resp){  
       
    alert(resp);  // we have the response
    $.modal.close();
       
    if (note == ""){
   	 	$("div."+key).replaceWith("");
     } else {
    	$("div."+key).replaceWith(function() {
  		var key = $("input#nKey").val();
   		var note = $("textarea#nNote").val();
  		return "<div class='note_img "+key+"' title=' - "+note+"' note='"+note+"' ><a href='DisplayNote?id="+key+"' rel='facebox'><img src=\"img/note_info.png\"></div>"
	});
	 	 $('a[rel*=facebox]').facebox(); 		
	 	}
  	 },
   	
   	error: function(e){  
     alert('Error: ' + e);  
   	}
   });
   
   return false;
    
  });  
});