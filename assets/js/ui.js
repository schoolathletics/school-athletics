jQuery(function($){

  // Set all variables to be used in scope
  var add_photo = jQuery('.add-photo');
  var delImgLink = jQuery('.delete-photo');
  
  // ADD IMAGE LINK
  add_photo.on( 'click', function( event ){
    
    event.preventDefault();
    

    var frame,
      metaBox = $(this).parent('.photo'), // Your meta box id here
      addImgLink = metaBox.find('.add-photo'),
      delImgLink = metaBox.find( '.delete-photo'),
      imgContainer = metaBox.find( '.thumbnail'),
      imgIdInput = metaBox.find( '.photo-id' );


    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    
    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100px;"/>' );

      // Send the attachment id to our hidden input
      imgIdInput.val( attachment.id );

      // Hide the add image link
      addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    frame.open();
  });
  
  
  // DELETE IMAGE LINK
  delImgLink.on( 'click', function( event ){

  	var frame,
      metaBox = $(this).parent('.photo'), // Your meta box id here
      addImgLink = metaBox.find('.add-photo'),
      delImgLink = metaBox.find( '.delete-photo'),
      imgContainer = metaBox.find( '.thumbnail'),
      imgIdInput = metaBox.find( '.photo-id' );


    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });

});


jQuery(function($){

	/*
	 * School Athletics Admin UI
	 */
	var SchoolAthletics = {

		/*
		 * Init School Athletics Admin UI
		 */
		init: function(){

			//Init sortable rows
			$( "#sortable" ).sortable({
				handle: ".handle",
				start: function(event, ui){ 
					ui.item.addClass('dragging');       
				},
				stop: function(event, ui){ 
					ui.item.removeClass('dragging');
					SchoolAthletics.updateFields();
				}
			});

		},

		/*
		 * Example Docs
		 */
		example: function(){

		},

		/*
		 * Example Docs
		 */
		addImage: function(){
			
		},

		/*
		 * Example Docs
		 */
		deleteImage: function(){
			
		},

		/*
		 * Update field input array with table rox index
		 * Modeled after https://wordpress.org/plugins/forms-3rdparty-integration/
		 */
		updateFields: function(){
			$('.clonable').find('input,select').each(function(i,o){
				var row_index = $(this).closest('tr').index();
				name = $(o).attr('name');
				$(o).attr('name', name.replace(/(athlete\[)([\d]+)/, '$1' + row_index));
				if($(o).hasClass('order')){
					$(o).val(row_index);
				}
			});
			console.log('Updated ID\'s');
		},

		/*
		 * Add Table Row
		 */
		addRow: function(row){
			var $tr    = $(row).closest('.clonable');
			var $clone = $tr.clone();
			$clone.find(':text').val('');
			$clone.find('select').attr('selected','');
			$clone.find(':hidden').val('');
			$clone.find('.thumbnail').empty();
			$tr.after($clone);
			console.log('Cloned');
			SchoolAthletics.updateFields()
		},

		/*
		 * Delete Table Row
		 */
		deleteRow: function(row){
			var $tr    = $(row).closest('.clonable');
			var $clone = $tr.remove();
			//Add input if their is an ID.
			console.log('removed');
			SchoolAthletics.updateFields()
		},

	}//end ui

	SchoolAthletics.init();
	$("a.add_row").live('click', function(){SchoolAthletics.addRow(this)});
	$("a.delete_row").live('click', function(){SchoolAthletics.deleteRow(this)});

});