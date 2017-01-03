/** 
 * This function loops through the dom an updates objects with new values
 * field  -  class to search for
 * value - new value for the field
 */

 function update_fields(field,value){
	console.log("update: "+field+" with: "+value);
	if($('.'+field).length){
		$('.'+field).each(function( index ) {
            // Buttons (on / off)
			if($(this).hasClass('btn')){
				console.log('toggeling button to: '+value);
				if(value == 'on'){
					$(this).removeClass('btn-default');
					$(this).addClass('btn-danger');
				}else{
					$(this).removeClass('btn-danger');
					$(this).addClass('btn-default');
				}

			}
            // Innerhtml
			if($(this).hasClass('txt')){
				console.log('changing text to: '+value);

				$(this).html(value);
			}
            // Input of type slider
			if($(this).hasClass('progress-bar')){
				console.log('changing value to: '+value);
            percentage = (value*100)/20;
				$(this).width(percentage+'%');
            $(this).html(value);
            $(this).attr('aria-valuenow',value)
			}
            // image src
			if($(this).hasClass('media-object')){
				if(value == 0){
				value = 'logo.png';
				}
				$(this).attr('src',value);
				console.log('changing image href to: '+value);
			}
            // list group
			if($(this).hasClass('list-group')){
				$(this).html(value);
				console.log('updating list with new elements: ');
			}
		});
	}
    // new events for new objects
	addEvents();
}
