$(function()
{
	$('form#campaignedit #from, form#campaignedit #to').datepicker
	({
		dateFormat: 'yy-mm-dd 00:00:00'
	});
	var attrs = $('form .attributes').append
	( 
		$( '<a />' )
			.toggle( function()
			{
				$(this).parent().find( 'input,select,textarea' ).attr( 'disabled', 'disabled' )
			}, function()
			{
				$(this).parent().find( 'input,select,textarea' ).removeAttr( 'disabled' )
			})
			.attr( 'href', 'javascript:void(0)' )
			.addClass( 'reset' )
			.text( 'x' ) 
	)
	$('form .add-attribute').click( function()
	{ 
		var lastAttr = $(this).parents('form:eq(0)').find('.attributes:last').parent();
		var newAttr = lastAttr.clone( true )
		var attrInputs = newAttr.find( 'input,select,textarea' ).removeAttr( 'disabled' ).val( '' );
		
		attrInputs.each( function() // zend form elem fix
		{
			var idx = $(this).attr( 'name' ).match( /(\d+)/gi );
			var newName = $(this).attr( 'name' ).replace( /(\d+)/gi, parseInt( idx[0] )+1 );
			$(this).attr( 'name', newName );
		})
		newAttr.insertAfter( lastAttr )
	})
});