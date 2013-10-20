<div class="container">
    <div class="col-xs-12">
        <h3>View Pictures</h3>
    </div>
</div>

<div class="container">
    <div class="col-xs-12 well well-sm">
        <div class="btn-group">
            <button id="btnSelectPictures" class="btn btn-default">Select Pictures</button>
        </div>
        <div class="btn-group">
            <button id="btnDeleteSelected" class="btn btn-danger">Delete <span class="glyphicon glyphicon-trash"></span></button>
            <button class="btn btn-default" disabled="disabled">Add To Album <span class="glyphicon glyphicon-book"></span></button>
        </div>
        <div id="notificationArea" class="pull-right">
        </div>
        <div class="clearfix">
        </div>
    </div>
</div>

<div id="allThumbnailsContainer" class="container">
    <?php
        $count  = 0;

        $perRowSmall = 2;
        $perRowMed   = 4;
        $perRowLarge = 6;

        foreach ($images as $imageId => $imageName) {
            $count++;

            $thumbImg = $imageBase.$imageName.'-thumb'.$imageExt;
            $fullImg  = $imageBase.$imageName.$imageExt;
    ?>
            <div class="thumbnailContainer col-xs-12 col-sm-6 col-md-3 col-lg-2">
                <img src="<?php echo $thumbImg;?>" class="thumbnail img-thumbnail" data-imageid="<?php echo $imageId;?>" data-fullimgsrc="<?php echo $fullImg;?>"/>
                <div class="overlay">
                    <span class="glyphicon glyphicon-check" style="display:none"></span>
                    <span class="glyphicon glyphicon-unchecked"></span>
                </div>
            </div>
    <?php
            if ($count % $perRowSmall === 0) {
                echo '<div class="clearfix visible-sm"></div>';
            }
            if ($count % $perRowMed === 0) {
                echo '<div class="clearfix visible-md"></div>';
            }
            if ($count % $perRowLarge === 0) {
                echo '<div class="clearfix visible-lg"></div>';
            }
        }
    ?>
</div>

<div id="fullscreenImageWrapper">
    <div id="fullscreenImage">
    </div>
</div>

<div class="modal-backdrop">
</div>

<div id="notificationTemplate" class="alert">
</div;>
