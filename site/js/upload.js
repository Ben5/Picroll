$(document).ready(function() {
    //
    // Global Vars
    //
    var numPhotosAdded = 0;
    var formData;


    //
    // Page Initialisation
    //


    //
    // Event Handler Registration
    //

    // Create a preview of images when the selected image changes.
    $('#photoUpload').on('change', CreatePreviewOnChange);

    // Submit button handler.
    $('#submit').on('click', SubmitHandler);


    //
    // Event Handlers
    //

    // Kick off the preview generation, as the selected files have changed
    function CreatePreviewOnChange() {
        // Remove old previews
        $('#previewContainer').empty();
        numPhotosAdded = 0;

        // Add new previews
        $.each($(this)[0].files, function() {
            var reader = new FileReader();
            reader.onload = PreviewReaderOnLoad;
            reader.readAsDataURL($(this)[0]);
        });
    }

    // Kick off the photo resizing and uploading
    function SubmitHandler() {
        var photoInput = $('#photoUpload');
        var photoFiles = photoInput[0].files;

        $.each(photoFiles, function(i, obj) {
            // Clear the (global) formdata object
            formData      = new FormData();

            // Get EXIF data and add it to formdata
            var reader    = new FileReader();
            reader.onload = function(readerEvent) {
                var binFile  = new BinaryFile(readerEvent.target.result,0,0);
                var exifData = EXIF.readFromBinaryFile(binFile);
                formData.append('exif', JSON.stringify(exifData));
            };
            reader.readAsBinaryString(obj);

            // Resize the image then do the upload with the formdata
            var resizeReader    = new FileReader();
            resizeReader.onload = function(readerEvent) {
                // Copy the loaded image file into an image object. 
                // Then, in the onload, we do the resize and upload
                var image    = new Image();
                image.onload = function() {
                    ImageOnLoad(image, i);
                };
                image.src    = readerEvent.target.result;
            };
            resizeReader.readAsDataURL(obj);
        });

        // Return false to stop the form actually submitting
        return false;
    }

    // Progress handler for xhr upload. 
    function XhrProgressListener(evt, requestId) {
        if (evt.lengthComputable) {
            var percentComplete = (Math.floor(evt.loaded / evt.total * 1000) / 10);
            var uploadWrapper = $('div.previewWrapper[data-photonum="'+requestId+'"]');
            uploadWrapper.find('div.uploadProgressText').html(percentComplete + '%');
            uploadWrapper.find('div.uploadProgressBar').css('width', percentComplete + '%');
        }
    }


    //
    // Callbacks
    //

    // OnLoad handler for preview filereader. Create a Preview image and attach it to page.
    function PreviewReaderOnLoad(e) {
        var wrapper         = $('<div>').addClass('previewWrapper col-md-3 col-sm-4 col-xs-6');
        var progressWrapper = $('<div>').addClass('uploadProgress');
        var progressText    = $('<div>').addClass('uploadProgressText');
        var progressBar     = $('<div>').addClass('uploadProgressBar');
        var img             = $('<img/>').addClass('uploadPreview img-thumbnail');

        img.attr('src', e.target.result);
        wrapper.attr('data-photonum', numPhotosAdded);
        numPhotosAdded++;
        
        wrapper.append(img);
        progressWrapper.append(progressBar);
        progressWrapper.append(progressText);
        wrapper.append(progressWrapper);
        $('#previewContainer').append(wrapper);
    }

    // OnLoad handler for resized image - prepare and upload image once loaded.
    function ImageOnLoad(thisImage, requestId) {
        var canvas  = document.createElement('canvas');
        var maxsize = 1200;
        var width   = thisImage.width;
        var height  = thisImage.height;
        
        // resize canvas to appropriate dimensions
        if(height > width) {
            // portrait picture, make the height the maxsize
            if(height > maxsize) {
                var ratio = height / maxsize;
                canvas.height = maxsize;
                canvas.width = width / ratio;
            }
        } else {
            // landscape picture, make the width the maxsize
            if(width > maxsize) {
                var ratio = width / maxsize;
                canvas.width = maxsize;
                canvas.height = height / ratio;
            }
        }

        canvas.getContext('2d').drawImage(thisImage, 0, 0, canvas.width, canvas.height);
        
        formData.append('uploadImage', canvas.toDataURL('image/jpeg'));

        // Finally, fire off the ajax request to do the upload
        var postUrl = '/picroll/json/upload/uploadFile';

        $.ajax({
            url: postUrl,
            type: 'POST',
            data: formData, 
            processData: false,
            contentType: false,
            xhr: function() {  // custom xhr
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // check if upload property exists
                    // for handling the progress of the upload
                    myXhr.upload.addEventListener(
                        'progress', 
                        function(evt) {
                            XhrProgressListener(evt, requestId);
                        }, 
                        false
                    );
                }
                return myXhr;
            },
            success: function(data) {
               // console.log(data);
            }
        });
    }

    //
    // Helpers
    //

});
