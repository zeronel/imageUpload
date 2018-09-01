function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#img').css('background-image','url('+e.target.result+')');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$('#image').change(function() {
    readURL(this);
});