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

    // Toggle Picture Select State
    $('img.thumbnail').on('click', ImageClickHandler);

    // Toggle the select state of images if the user clicks the overlay directly, not the image
    $('div.overlay').on('click', function() {ToggleSelectState($(this).siblings('img'));})

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
    }

    // Handle clicks on thumbnails
    function ImageClickHandler() {
        if ($('div.overlay').is(':visible')) {
            ToggleSelectState($(this));
        } else {
            // Show the full screen image!
            var fullImageSrc = $(this).data('fullimgsrc');
            var fullImage = new Image();
            fullImage.src = fullImageSrc;
            $('#fullscreenImageWrapper').fadeIn(400);
            $('#fullscreenImage').empty().append(fullImage);

            // add a faded background
            $('div.modal-backdrop').fadeIn(400)
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

    //
    // Helpers
    //

    // Toggle the Select State of pictures
    function ToggleSelectState(thisImg) {
        thisImg.siblings('div.overlay').find('span').each(function() { $(this).toggle(); });
    }
});
