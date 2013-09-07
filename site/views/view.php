<div class="container">
    <div class="col-xs-12">
        <h3>View Pictures</h3>
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
    ?>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                <img src='<?php echo $imageBase.$imageName.$imageExt;?>' class="thumbnail img-thumbnail"/>
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
