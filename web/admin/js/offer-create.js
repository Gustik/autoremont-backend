$('#offer-create-phone').keyup(function() {
	$('#offer-create-name').val('')
	$('#author_id').val('')
	$('#offer-create-phone').parent().addClass('has-error')
	$('#offer-create-name').parent().addClass('has-error')
	$('#offer-create-phone').parent().removeClass('has-success')
	$('#offer-create-name').parent().removeClass('has-success')
	$.get('/admin/offer/find-user', {query: $('#offer-create-phone').val()}, function(user) {
		if (user.profile) {
			$('#offer-create-name').val(user.profile.name)
			$('#author_id').val(user.id)
			$('#offer-create-phone').parent().addClass('has-success')
			$('#offer-create-name').parent().addClass('has-success')
			$('#offer-create-phone').parent().removeClass('has-error')
			$('#offer-create-name').parent().removeClass('has-error')
		}
	}, 'JSON')
})