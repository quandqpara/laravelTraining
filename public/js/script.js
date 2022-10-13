$("#name").keyup(function (event) {
    var text = $("#name").val();
    if (text.length > 128) {
        $("name").val(text.substring(0, 128));
    }
})
