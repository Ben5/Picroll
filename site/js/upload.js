$(document).ready(function() {

    var numPhotosAdded = 0;
    var uploadWrapper; 
    var formData;

    // OnLoad handler for preview filereader. Create a Preview image and attach it to page.
    var PreviewReaderOnLoad = function(e) {
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

    // Progress handler for xhr upload. 
    var XhrProgressListener = function(evt) {
        if (evt.lengthComputable) {
            var percentComplete = (Math.floor(evt.loaded / evt.total * 1000) / 10);
            uploadWrapper.find('div.uploadProgressText').html(percentComplete + '%');
            uploadWrapper.find('div.uploadProgressBar').css('width', percentComplete + '%');
        }
    }

    // Create a preview of images when the selected image changes.
    $('#photoUpload').on('change', function() {
        // Remove old previews
        $('#previewContainer').empty();
        numPhotosAdded = 0;

        // Add new previews
        $.each($(this)[0].files, function() {
            var reader = new FileReader();
            reader.onload = PreviewReaderOnLoad;
            reader.readAsDataURL($(this)[0]);
        });

    });

    var ImageOnLoad = function() {
        console.log('loaded');

        var canvas  = document.createElement('canvas');
        var maxsize = 1200;
        var width   = this.width;
        var height  = this.height;
        
        // resize canvas to appropriate dimensions
        if(height > width)
        {
            // portrait picture, make the height the maxsize
            if(height > maxsize) 
            {
                var ratio = height / maxsize;
                canvas.height = maxsize;
                canvas.width = width / ratio;
            }
        }
        else 
        {
            // landscape picture, make the width the maxsize
            if(width > maxsize)
            {
                var ratio = width / maxsize;
                canvas.width = maxsize;
                canvas.height = height / ratio;
            }
        }

        canvas.getContext('2d').drawImage(this, 0, 0, canvas.width, canvas.height);
        $('body').append(canvas);
        
        formData.append('uploadImage', canvas.toDataURL('image/jpeg'));


        var postUrl    = '/picroll/json/upload/uploadFile';

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
    };

    // Submit button handler.
    $('#submit').on('click', function() {
        var photoInput = $('#photoUpload');
        var photoFiles = photoInput[0].files;

        $.each(photoFiles, function(i, obj) {
            formData = new FormData();
            uploadWrapper = $('div.previewWrapper[data-photonum="'+i+'"]');

            // Resize the image
            // TODO: SPLIT THIS UP INTO TWO FILE READERS - ONE FOR EXIF, ONE FOR UPLOAD
            var reader = new FileReader();
            reader.onload = function(readerEvent) {

                // get EXIF data
                var binFile = new BinaryFile(readerEvent.target.result,0,0);
                var exifData = EXIF.readFromBinaryFile(binFile)
                formData.append('exif', JSON.stringify(exifData));


            };
            reader.readAsBinaryString(obj);

            var goodReader = new FileReader();
            goodReader.onload = function(readerEvent) {
                var image = new Image();
                //image.src = '/picroll/images/uploads/DSCN2745.JPG';
                $('body').append(image);
                image.src = readerEvent.target.result;
                image.onload = ImageOnLoad;
            };
            goodReader.readAsDataURL(obj);
        });


        return false;
    });
});
