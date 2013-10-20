$(document).ready(function() {
    //
    // Global Vars
    //

    //
    // Page Initialisation
    //

    //
    // Event Handler Registration
    //

    // Select Images button click
    $('#btnSelectPictures').on('click', ToggleSelectMode);

    // Delete Images button click
    $('#btnDeleteSelected').on('click', DeleteSelectedPictures);

    // Add Images to New Album button click
    $('#createNewAlbum').on('click', AddSelectedImagesToNewAlbum);

    // Add Images to New Album button click
    $('#btnAddSelectedToAlbum a.existingAlbum').on('click', AddSelectedImagesToExistingAlbum);

    // Toggle Picture Select State
    $('img.thumbnail').on('click', ImageClickHandler);

    // Toggle the select state of images if the user clicks the overlay directly, not the image
    $('div.overlay').on('click', function() {ToggleSelectState($(this).siblings('img'));});

    // Click on the full size image overlay backdrop
    $('#fullscreenImage').on('click', HideFullscreenImage);
    $('#fullscreenImage').on('click', 'img',  function(event) {event.stopPropagation();}); // we dont want to call HideFullscreenImage if we click the image itself


    //
    // Event Handlers
    //

    // Toggle the Select Pictures mode
    function ToggleSelectMode() {
        var $overlays = $('div.overlay');
        $overlays.toggle(200);
        $overlays.find('span.glyphicon-check').hide();
        $overlays.find('span.glyphicon-unchecked').show();

        if ($(this).hasClass('btn-default')) {
            $(this).addClass('btn-primary').removeClass('btn-default');
        } else {
            $(this).addClass('btn-default').removeClass('btn-primary');
        }
    }

    // Toggle the Select Pictures mode
    function DeleteSelectedPictures() {
        var pictureIds = GetAllSelectedPictureIds();
        if (pictureIds.length === 0) {
            return;
        }

        bootbox.confirm(
            'Do you want to delete ' + pictureIds.length + ' pictures?',
            function(result) {
                if (result) {
                    // Finally, fire off the ajax request to do the upload
                    var url = '/picroll/json/view/deleteimages';
                    var dataObj = {
                        imageIds: pictureIds
                    };

                    $.ajax({
                        url:         url,
                        type:        'POST',
                        data:        dataObj, 
                        success:     function(data) { DeletePicturesCB(data, pictureIds); }
                    });
                }
            }
        );
    }

    // Create a new album, and add the selected images to it
    function AddSelectedImagesToNewAlbum() {
        var pictureIds = GetAllSelectedPictureIds();
        if (pictureIds.length === 0) {
            return;
        }

        bootbox.prompt(
            'Name your new Album',
            function(result) {
                if (result.length > 0) {
                    var url = '/picroll/json/view/newalbum';
                    var dataObj = {
                        albumName : result,
                        pictureIds : pictureIds
                    };

                    $.ajax({
                        url:    url,
                        data:   dataObj,
                        type:   'POST',
                        success: function(data) {console.log(data);}
                    });
                }
            }
        );

    }

    // Create a new album, and add the selected images to it
    function AddSelectedImagesToExistingAlbum() {
        var pictureIds = GetAllSelectedPictureIds();
        if (pictureIds.length === 0) {
            return;
        }

        var albumId = $(this).data('albumid');

        var url = '/picroll/json/view/addtoalbum';
        var dataObj = {
            albumId :    albumId,
            pictureIds : pictureIds
        };

        $.ajax({
            url:    url,
            data:   dataObj,
            type:   'POST',
            success: function(data) {console.log(data);}
        });
    }

    // Handle clicks on thumbnails
    function ImageClickHandler() {
        if ($(this).siblings('div.overlay').is(':visible')) {
            ToggleSelectState($(this));
        } else {
            // Show the full screen image!
            var fullImageSrc = $(this).data('fullimgsrc');
            var fullImage = new Image();
            fullImage.src = fullImageSrc;
            $('#fullscreenImageWrapper').fadeIn(400);
            $('#fullscreenImage').empty().append(fullImage);

            // add a faded background
            $('div.modal-backdrop').fadeIn(400);
        }
    }

    // Hide Fullscreen Image
    function HideFullscreenImage() {
        // hide the image
        $('#fullscreenImageWrapper').fadeOut(400);

        // hide the overlay
        $('div.modal-backdrop').fadeOut(400);
    }

    //
    // Callbacks
    //

    function DeletePicturesCB(data, pictureIds) {
        // We have deleted the images from the server, now delete the from the page
        for (var index in pictureIds) {
            $('#allThumbnailsContainer').find('img[data-imageid="'+pictureIds[index]+'"]').parents('div.thumbnailContainer').remove();
        }

        // now show a notification
        ShowThenHideMessage('success', 'Pictures Deleted Successfully!', $('#notificationArea').empty());

        // now redo the clearfixes, so we dont have gaps at the end of rows
        RedoClearfixDivs();
    }

    //
    // Helpers
    //

    // Toggle the Select State of pictures
    function ToggleSelectState(thisImg) {
        thisImg.siblings('div.overlay').find('span').each(function() { $(this).toggle(); });
    }

    // Show a confirmation message then hide it again
    function ShowThenHideMessage(type, message, parentDiv) {
        // which class to add
        var typeClass;
        switch (type) {
            case 'success':
                typeClass = 'alert-success';
                break;
            case 'warning':
                typeClass = 'alert-warning';
                break;
            case 'error':
            case 'danger':
                typeClass = 'alert-danger';
                break;
            default: 
                typeClass = 'alert-info';
        }
        
        var notification = $('#notificationTemplate').clone();

        notification.addClass(typeClass)
                    .text(message)
                    .removeAttr('id')
                    .hide();

        parentDiv.append(notification);

        // Show the message
        notification.show(400, function() {
            // Now, wait 2 seconds
            var intervalHandle = setInterval(function() {
                    // Then hide the message again
                    notification.hide(400, function() {
                        // Clear the interval
                        clearInterval(intervalHandle);
                        // Finally remove the hidden message entirely
                        notification.remove();
                    });
                }, 
                2000
            );
        });
    }

    // Re-position the responsive clearfix divs throughout the list of pictures.
    function RedoClearfixDivs() {
        var allThumbnailsContainer = $('#allThumbnailsContainer');

        allThumbnailsContainer.find('div.clearfix').remove();

        var thumbnailIndex = 0;
        var clearfixDiv = $('<div>').addClass('clearfix');

        allThumbnailsContainer.find('div.thumbnailContainer').each(function() {
            thumbnailIndex++;

            if (thumbnailIndex % 2 === 0) {
                clearfixDiv.clone()
                           .addClass('visible-sm')
                           .insertAfter($(this));
            }
            if (thumbnailIndex % 4 === 0) {
                clearfixDiv.clone()
                           .addClass('visible-md')
                           .insertAfter($(this));
            }
            if (thumbnailIndex % 6 === 0) {
                clearfixDiv.clone()
                           .addClass('visible-lg')
                           .insertAfter($(this));
            }
        });
    }

    // Get a list of all selected picture ids
    function GetAllSelectedPictureIds() {
        var selectedPictures = $('#allThumbnailsContainer')
                                   .find('span.glyphicon-check')
                                   .filter(':visible')
                                   .parents('div.overlay')
                                   .siblings('img');

        var pictureIds = [];
        selectedPictures.each(function() {
            pictureIds.push($(this).data('imageid'));
        });

        return pictureIds;
    }
});
