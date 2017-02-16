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
			if($().sortable){
				console.log('has sortable');
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
			}

			SchoolAthletics.datetimepickerStart();

		},

		/*
		 * Example Docs
		 */
		example: function(){

		},

		/*
		 * Example Docs
		 */
		datetimepickerStart: function(){
			if($().datetimepicker){
				console.log('has datepicker');
				$('.datetime').datetimepicker({
					controlType: 'select',
					oneLine: true,
					timeFormat: 'hh:mm tt'
				});
			}
		},

		/*
		 * Example Docs
		 */
		addImage: function(button){
			var frame,
			metaBox = $(button).parent('.photo'), // Your meta box id here
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
				title: 'Select or Upload a Phone',
				button: {
					text: 'Use this Photo'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

    
			// When an image is selected in the media frame...
			frame.on( 'select', function() {
				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:250px;"/>' );

				// Send the attachment id to our hidden input
				imgIdInput.val( attachment.id );

				// Hide the add image link
				metaBox.removeClass( 'no' );
				metaBox.addClass( 'yes' );

			});

			// Finally, open the modal on click
			frame.open();
		},

		/*
		 * Example Docs
		 */
		removeImage: function(button){
			console.log('Delete');
			var frame,
			metaBox = $(button).parent('.photo'), // Your meta box id here
			addImgLink = metaBox.find('.add-photo'),
			delImgLink = metaBox.find( '.delete-photo'),
			imgContainer = metaBox.find( '.thumbnail'),
			imgIdInput = metaBox.find( '.photo-id' );

			// Clear out the preview image
			imgContainer.html( '' );

			// Un-hide the add image link
			metaBox.removeClass( 'yes' );
			metaBox.addClass( 'no' );

			// Delete the image id from the hidden input
			imgIdInput.val( '' );
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
				$(o).attr('id', name.replace(/(athlete\[)([\d]+)/, '$1' + row_index));
				$(o).attr('name', name.replace(/(event\[)([\d]+)/, '$1' + row_index));
				$(o).attr('id', name.replace(/(event\[)([\d]+)/, '$1' + row_index));
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
			var $tr = $(row).closest('.clonable');
			var $clone = $tr.clone();
			$clone.find('input[type="hidden"]').val('');
			$clone.find('select').attr('selected','');
			$clone.find(':text').val('');
			$clone.find('.thumbnail').empty();
			$clone.find('.photo').removeClass('yes');
			if(!$clone.find('.photo').hasClass('no')){
				$clone.find('.photo').addClass('no');
			}
			$tr.after($clone);
			console.log('Cloned');
			$(".hasDatepicker").removeClass("hasDatepicker");
			$('.datetime').datetimepicker('destroy');
			SchoolAthletics.updateFields();
			SchoolAthletics.datetimepickerStart();
		},

		/*
		 * Delete Table Row
		 */
		deleteRow: function(row){
			var $tr = $(row).closest('.clonable');
			var id = $tr.find('.object_id').val();
			//Add input if their is an ID.
			$('#tobedeleted').append('<input type="hidden" name="deleteObjects[]" value="'+id+'">');
			$tr.fadeOut(300, function() { $(this).remove(); });
			//$tr.remove();
			console.log('removed');
			SchoolAthletics.updateFields()
		},

	}//end ui

	SchoolAthletics.init();
	$("a.add-photo").live('click', function(event){
		e.preventDefault();
		SchoolAthletics.addImage(this)
	});
	$("a.remove-photo").live('click', function(event){
		e.preventDefault();
		SchoolAthletics.removeImage(this)
	});

	$("a.add_row").live('click', function(){SchoolAthletics.addRow(this)});
	$("a.delete_row").live('click', function(){SchoolAthletics.deleteRow(this)});

});