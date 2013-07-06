$(document).ready(function() {

    var numPhotosAdded = 0;
    var uploadWrapper; 

    var ReaderOnLoad = function(e) {
        var wrapper         = $('<div class="previewWrapper"></div>');
        var progressWrapper = $('<div class="uploadProgress"></div>');
        var progressText    = $('<div class="uploadProgressText"></div>');
        var progressBar     = $('<div class="uploadProgressBar"></div>');
        var img             = $('<img class="uploadPreview"/>');

        img.attr('src', e.target.result);
        wrapper.attr('data-photonum', numPhotosAdded);
        numPhotosAdded++;
        
        wrapper.append(img);
        progressWrapper.append(progressBar);
        progressWrapper.append(progressText);
        wrapper.append(progressWrapper);
        $('#previewContainer').append(wrapper);
    };

    var XhrProgressListener = function(evt) {
        if (evt.lengthComputable) {
            var percentComplete = Math.floor(evt.loaded / evt.total * 100);
            uploadWrapper.find('div.uploadProgressText').html(percentComplete + '%');
            uploadWrapper.find('div.uploadProgressBar').css('width', percentComplete + '%');
        }
    }

    $('#photoUpload').on('change', function() {
        // Remove old previews
        $('#previewContainer').empty();
        numPhotosAdded = 0;

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
        var postUrl    = '/picroll/json/upload/uploadFile';
        var photoInput = $('#photoUpload');
        var photoFiles = photoInput[0].files;

        $.each(photoFiles, function(i, obj) {
            var formData = new FormData();

            formData.append('uploadImage', obj);

            uploadWrapper = $('div.previewWrapper[data-photonum="'+i+'"]');

            $.ajax({
                url: postUrl,
                type: 'POST',
                data: formData, 
                processData: false,
                contentType: false,
                xhr: function() {  // custom xhr
                        myXhr = $.ajaxSettings.xhr();
                        if(myXhr.upload){ // check if upload property exists
                            myXhr.upload.addEventListener('progress', XhrProgressListener, false); // for handling the progress of the upload
                        }
                        return myXhr;
                    },
                success: function(data) {
                        console.log(data);
                    }
            });
        });


        return false;
    });
});
