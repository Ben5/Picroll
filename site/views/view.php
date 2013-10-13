<div class="container">
    <div class="col-xs-12">
        <h3>View Pictures</h3>
    </div>
</div>

<div class="container">
    <div class="col-xs-12 well well-sm">
        <h5>Edit Pictures</h5>
        <div class="btn-group">
            <button id="btnSelectPictures" class="btn btn-default">Select Pictures</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-danger" disabled="disabled">Delete <span class="glyphicon glyphicon-trash"></span></button>
            <button class="btn btn-default" disabled="disabled">Add To Album <span class="glyphicon glyphicon-book"></span></button>
        </div>
    </div>
</div>

<div class="container">
    <?php
        $countSmall  = 0;
        $countMed    = 0;
        $countLarge  = 0;

        $perRowSmall = 2;
        $perRowMed   = 4;
        $perRowLarge = 6;

        foreach ($images as $imageName) {
            $countSmall++;
            $countMed++;
            $countLarge++;

            $thumbImg = $imageBase.$imageName.'-thumb'.$imageExt;
            $fullImg  = $imageBase.$imageName.$imageExt;
    ?>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                <img src="<?php echo $thumbImg;?>" class="thumbnail img-thumbnail" data-fullimgsrc="<?php echo $fullImg;?>"/>
                <div class="overlay">
                    <span class="glyphicon glyphicon-check" style="display:none"></span>
                    <span class="glyphicon glyphicon-unchecked"></span>
                </div>
            </div>
    <?php
            if ($countSmall === $perRowSmall) {
                echo '<div class="clearfix visible-sm"></div>';
                $countSmall = 0;
            }
            if ($countMed === $perRowMed) {
                echo '<div class="clearfix visible-md"></div>';
                $countMed = 0;
            }
            if ($countLarge === $perRowLarge) {
                echo '<div class="clearfix visible-lg"></div>';
                $countLarge = 0;
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
