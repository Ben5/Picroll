$(document).ready(function() {
    //
    // Global Vars
    //
    var allSourceImages    = $('img.hide');
    var slideshowHostImage = $('#slideshow');
    var currentImageIndex  = 1;
    var maxImageIndex      = allSourceImages.length + 1;
    var currentlyPlaying   = false;
    var intervalHandle     = null;
    var imageTime          = 200;
    var loopForwards       = true;

    //
    // Page Initialisation
    //
    LoopImages();

    $('#zoomInBtn, #zoomOutBtn').attr('disabled', true);


    //
    // Event Handler Registration
    //
    $('#playPauseToggleBtn').on('click', PlayPauseToggle);

    $('#nextBtn').on('click', NextImage);

    $('#prevBtn').on('click', PrevImage);

    //
    // Event Handlers
    //
    function PlayPauseToggle() 
    {
        if (currentlyPlaying) {
            clearInterval(intervalHandle);
            $(this).text('Play');

            $('#prevBtn, #nextBtn').removeAttr('disabled');
        } else {
            intervalHandle = setInterval(LoopImages, imageTime)
            $(this).text('Pause');

            $('#prevBtn, #nextBtn').attr('disabled', true);
        }

        currentlyPlaying = !currentlyPlaying;
    }

    function NextImage()
    {
        loopForwards = true;
        LoopImages();
    }

    function PrevImage()
    {
        loopForwards = false;
        LoopImages();
    }

    //
    // Callbacks
    //

    //
    // Helpers
    //
    function LoopImages()
    {
        if (loopForwards) {
            currentImageIndex++;
            if (currentImageIndex > maxImageIndex) {
                currentImageIndex = 2;
            }
        } else {
            currentImageIndex--;
            if (currentImageIndex < 2) {
                currentImageIndex = maxImageIndex;
            }
        }

        var imageToUse = allSourceImages.filter(':nth-child('+currentImageIndex+')');

        slideshowHostImage.attr('src', imageToUse.attr('src'));
        console.log(allSourceImages);
        console.log(allSourceImages.filter(':nth-child(1)'));
        console.log(currentImageIndex, maxImageIndex);
        console.log(imageToUse.attr('src'));
        
    }
});
