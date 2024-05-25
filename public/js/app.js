function handleValidationErrors(errors) {
    // Clear all previous errors
    $('input').removeClass('border-red-500');
    $('.text-red-500').text('');

    // Set new errors
    $.each(errors, function(field, messages) {
        var input = $('[name="' + field + '"]');
        input.addClass('border-red-500');
        input.next('.text-red-500').text(messages[0]);
    });
}

