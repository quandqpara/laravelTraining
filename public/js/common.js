
$(function () {
    $(document).on('click', '.del-btn', function () {
        let url = $(this).data('url');
        $('#exampleModal').find('form').prop('action', url);
    })
})
