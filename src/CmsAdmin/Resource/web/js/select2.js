$(document).ready(function () {
    $select = $('.select2');
    $select.find('option:disabled').each(function () {
        $(this).attr('selected', false);
    })
    $select.select2({
        width: '100%',
        placeholder: '',
    });
});
