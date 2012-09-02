confirmMessage = function( title, body, link, linkText ) {
		$('.modal-header h3').html(title);
		$('.modal-body').html(body);
		$('#modal-link').attr('href', link);
		$('#modal-link').html(linkText);
		$('#confirmationModal').modal();
};
