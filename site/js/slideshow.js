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
    var minImageTime       = 50;
    var maxImageTime       = 500;
    var imageTime          = 200;
    var imageTimeSpeedStep = 50;
    var loopForwards       = true;

    //
    // Page Initialisation
    //
    LoopImages();

    $('#zoomInBtn, #zoomOutBtn').attr('disabled', true);

    $('#slideshow').zoomify();


    //
    // Event Handler Registration
    //
    $('#playPauseToggleBtn').on('click', PlayPauseToggle);

    $('#nextBtn').on('click', NextImage);

    $('#prevBtn').on('click', PrevImage);

    $('#fastBtn').on('click', SpeedUp);

    $('#slowBtn').on('click', SlowDown);

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
            intervalHandle = setInterval(LoopImages, imageTime);
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

    function SpeedUp()
    {
        if (imageTime > minImageTime) {
            imageTime -= imageTimeSpeedStep;

            RestartIfPlaying();

            $('#slowBtn').prop('disabled', false);
        } else {
            $(this).prop('disabled', true);
        }
    }

    function SlowDown()
    {
        if (imageTime < maxImageTime) {
            imageTime += imageTimeSpeedStep;
            
            RestartIfPlaying();

            $('#fastBtn').prop('disabled', false);
        } else {
            $(this).prop('disabled', true);
        }
    }

    function RestartIfPlaying()
    {
        if (currentlyPlaying) {
            clearInterval(intervalHandle);
            intervalHandle = setInterval(LoopImages, imageTime);
        }
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
    }
});
