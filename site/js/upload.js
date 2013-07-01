$(document).ready(function() {

    var numPhotosAdded = 0;
    var ReaderOnLoad = function(e) {
        var img = $('<img class="uploadPreview" width="300px"/>');
        img.attr('src', e.target.result);
        $('#previewContainer').append(img);
    };


    $('#photoUpload').on('change', function() {

        // Remove old previews
        $('#previewContainer').find('img.uploadPreview').remove();

        // Add new previews
        $.each($(this)[0].files, function() {
            console.log($(this));
            var reader = new FileReader();
            reader.onload = ReaderOnLoad;
            reader.readAsDataURL($(this)[0]);
        });

    });

    // Submit button handler
    $('#submit').on('click', function() {
        var url = '/picroll/json/upload/uploadFile';
        var data = {name: 'ben'};

        $.post(
            url, 
            data, 
            function(data) {
                console.log(data);
            }
        );
        return false;
    });
});
