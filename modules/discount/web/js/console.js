$(document).ready(function() {
    $('#discountform-code').pincodeInput({
        hidedigits: false, inputs: 6
    }).pincodeInput().data('plugin_pincodeInput').focus();
});