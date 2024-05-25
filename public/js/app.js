function handleValidationErrors(errors) {
    $('input').removeClass('border-red-500');
    $('.text-red-500').text('');

    $.each(errors, function(field, messages) {
        const input = $('[name="' + field + '"]');
        input.addClass('border-red-500');
        input.next('.text-red-500').text(messages[0]);
    });
}

