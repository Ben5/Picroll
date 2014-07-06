<?php $this->AddScript('zoom.js'); ?>
<div class="container">
    <div class="col-xs-12">
        <h3>Slideshow</h3>
    </div>
</div>

<?php
    // Load in all the images, but hidden
    foreach ($allImages as $imageName) {
?>
        <img class="hide" src="<?php echo $imageBase.$imageName.$imageExt;?>">
<?php
    }
    
?>

<div class="container">
    <div class="col-xs-12">
        <!-- Now create an image which will cycle through all the hidden ones! -->
        <div class="row">
            <img id="slideshow" class="slideshow" />
        </div>
    </div>

    <!-- controls -->
    <div class="col-xs-12">
        <div class="btn-group">
            <button id="playPauseToggleBtn" class="btn btn-sm btn-default">Play</button>
        </div>
        <div class="btn-group">
            <button id="zoomInBtn"  class="btn btn-sm btn-default">Zoom In</button>
            <button id="zoomOutBtn" class="btn btn-sm btn-default">Zoom Out</button>
        </div>
        <div class="btn-group">
            <button id="prevBtn"    class="btn btn-sm btn-default">Prev</button>
            <button id="nextBtn"    class="btn btn-sm btn-default">Next</button>
        </div>
        <div class="btn-group">
            <button id="slowBtn"    class="btn btn-sm btn-default">Slower</button>
            <button id="fastBtn"    class="btn btn-sm btn-default">Faster</button>
        </div>
    </div>
</div>

