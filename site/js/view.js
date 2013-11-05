$(document).ready(function() {
    //
    // Global Vars
    //
    var currentAlbum = 0;

    //
    // Page Initialisation
    //

    //
    // Event Handler Registration
    //

    // Select Images (or Albums) button click
    $('#btnSelectPictures, #btnSelectAlbums').on('click', ToggleSelectMode);

    // Delete Images button click
    $('#btnRemoveSelected').on('click', RemoveSelectedPictures);

    // Delete Images button click
    $('#btnDeleteSelected').on('click', DeleteSelectedPictures);

    // Delete Images button click
    $('#btnDeleteSelectedAlbums').on('click', DeleteSelectedAlbums);

    // Add Images to New Album button click
    $('#createNewAlbum').on('click', AddSelectedImagesToNewAlbum);

    // Add Images to New Album button click
    $('#btnAddSelectedToAlbum a.existingAlbum').on('click', AddSelectedImagesToExistingAlbum);

    // Toggle Picture Select State
    $('#allThumbnailsContainer').on('click', 'img.thumbnail', ImageClickHandler);

    // Toggle Picture Select State
    $('#allAlbumsContainer').find('img.thumbnail').on('click', AlbumClickHandler);

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

    // Delete the selected pictures
    function RemoveSelectedPictures() {
        var pictureIds = GetAllSelectedItemIds('allThumbnailsContainer', 'imageid');
        if (pictureIds.length === 0) {
            return;
        }

        bootbox.confirm(
            'Do you want to remove ' + pictureIds.length + ' picture' + (pictureIds.length > 1 ? 's' : '') + ' from this Album?',
            function(result) {
                if (result) {
                    // Finally, fire off the ajax request to do the upload
                    var url = '/picroll/json/view/removeimagesfromalbum';
                    var dataObj = {
                        imageIds: pictureIds,
                        albumId: currentAlbum
                    };

                    $.ajax({
                        url:         url,
                        data:        dataObj, 
                        type:        'POST',
                        dataType:    'JSON',
                        success:     function(data) { 
                            // Remove the image from display, show success feedback, etc 
                            DeletePicturesCB(data, pictureIds); 
                        }
                    });
                }
            }
        );
    }

    // Delete the selected pictures
    function DeleteSelectedPictures() {
        var pictureIds = GetAllSelectedItemIds('allThumbnailsContainer', 'imageid');
        if (pictureIds.length === 0) {
            return;
        }

        bootbox.confirm(
            'Do you want to delete ' + pictureIds.length + ' picture' + (pictureIds.length > 1 ? 's' : '') + '?',
            function(result) {
                if (result) {
                    // Finally, fire off the ajax request to do the upload
                    var url = '/picroll/json/view/deleteimages';
                    var dataObj = {
                        imageIds: pictureIds
                    };

                    $.ajax({
                        url:         url,
                        data:        dataObj, 
                        type:        'POST',
                        dataType:    'JSON',
                        success:     function(data) { 
                            // Remove the image from display, show success feedback, etc 
                            DeletePicturesCB(data, pictureIds); 

                            // Now we need to re-generate the album thumbnail (if we deleted the first image!)
                        }
                    });
                }
            }
        );
    }

    // Delete the selected albums
    function DeleteSelectedAlbums() {
        var albumIds = GetAllSelectedItemIds('allAlbumsContainer', 'albumid');
        if (albumIds.length === 0) {
            return;
        }

        bootbox.confirm(
            'Do you want to delete ' + albumIds.length + ' album' + (albumIds.length > 1 ? 's' : '') + '?<br>'
            +'(You won\'t lose any pictures)',
            function(result) {
                if (result) {
                    // Finally, fire off the ajax request to do the upload
                    var url = '/picroll/json/view/deletealbums';
                    var dataObj = {
                        albumIds: albumIds
                    };
console.log(dataObj);

                    $.ajax({
                        url:         url,
                        data:        dataObj, 
                        type:        'POST',
                        dataType:    'JSON',
                        success:     function(data) { DeleteAlbumsCB(data, albumIds); }
                    });
                }
            }
        );
    }

    // Create a new album, and add the selected images to it
    function AddSelectedImagesToNewAlbum() {
        var pictureIds = GetAllSelectedItemIds('allThumbnailsContainer', 'imageid');
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
                        url:      url,
                        data:     dataObj,
                        type:     'POST',
                        dataType: 'JSON',
                        success: function(data) {
                            // Add new album to the list
                            var newA = $('<a>', {href: '#'}).addClass('existingAlbum');
                            var newLi = $('<li>').append(newA);
                            newLi.insertBefore($('#btnAddSelectedToAlbum').find('ul').find('li.divider'));
                        }
                    });
                }
            }
        );

    }

    // Create a new album, and add the selected images to it
    function AddSelectedImagesToExistingAlbum() {
        var pictureIds = GetAllSelectedItemIds('allThumbnailsContainer', 'imageid');
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
            url:      url,
            data:     dataObj,
            type:     'POST',
            dataType: 'JSON',
            success:  function(data) {
                // TODO: show feedback
                console.log(data);
            }
        });
    }

    // Handle clicks on thumbnails
    function ImageClickHandler() {
        console.log('click');
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

    // Handle clicks on Albums
    function AlbumClickHandler() {
        if ($(this).siblings('div.overlay').is(':visible')) {
            ToggleSelectState($(this));
        } else {
            // Load in all the album pictures (unless already loaded!)
            var albumId = $(this).data('albumid');
            if (currentAlbum == albumId) {
                return;
            }
            
            currentAlbum = albumId;

            var url = '/picroll/json/view/getalbumcontents';
            var dataObj = {
                albumId : albumId
            };

            $.ajax({
                url:      url,
                data:     dataObj,
                type:     'POST',
                dataType: 'JSON',
                success:  ShowAlbumImages
            });

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
        var successMessage = 'Pictures Deleted Successfully!';
        if (pictureIds.length <= 1) {
            successMessage = 'Picture Deleted Successfully!';
        }

        ShowThenHideMessage('success', successMessage, $('#pictureNotificationArea').empty());

        // now redo the clearfixes, so we dont have gaps at the end of rows
        RedoClearfixDivs();
    }

    function DeleteAlbumsCB(data, albumIds) {
        // We have deleted the albums from the server, now delete the from the page
        for (var index in albumIds) {
            $('#allAlbumsContainer').find('img[data-albumid="'+albumIds[index]+'"]').parents('div.thumbnailContainer').remove();
        }

        // now show a notification
        var successMessage = 'Albums Deleted Successfully!';
        if (albumIds.length <= 1) {
            successMessage = 'Album Deleted Successfully!';
        }

        ShowThenHideMessage('success', successMessage, $('#albumNotificationArea').empty());

        // now redo the clearfixes, so we dont have gaps at the end of rows
        RedoClearfixDivs();
    }

    function ShowAlbumImages(data) {

        var images = data.images;
        $('#allThumbnailsContainer').empty();

            //$thumbImg = $imageBase.$imageName.'-thumb'.$imageExt;

        $.each(images, function(key, value) {
            var imageId         = key;
            var imageFullImgSrc = '/picroll/images/uploads/' + value + '.jpeg'            
            var imageSrc        = '/picroll/images/uploads/' + value + '-thumb.jpeg';

            var newThumbnail = $('#thumbnailTemplate').find('div.thumbnailContainer').clone();

            newThumbnail.find('img')
                        .attr('data-imageid', imageId)
                        .attr('data-fullimgsrc', imageFullImgSrc)
                        .attr('src', imageSrc);

            $('#allThumbnailsContainer').append(newThumbnail);
        });

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
    function RedoClearfixDivs() { // TODO: make this work for both thumbnails AND albums
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
    function GetAllSelectedItemIds(parentId, dataName) {
        var selectedPictures = $('#'+parentId)
                                   .find('span.glyphicon-check')
                                   .filter(':visible')
                                   .parents('div.overlay')
                                   .siblings('img');

        var pictureIds = [];
        selectedPictures.each(function() {
            pictureIds.push($(this).data(dataName));
        });

        return pictureIds;
    }
});
