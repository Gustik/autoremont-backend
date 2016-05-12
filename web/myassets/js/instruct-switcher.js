jQuery('.feature').mouseenter(function(event) {
    var id = jQuery(this).data('instruction-id')
    jQuery('.instruction:visible').hide()
    jQuery(id).fadeIn('100')
})