<div class="container">
    <div class="col-xs-12">
        <h3>View Pictures</h3>
    </div>
</div>

<div class="container">
    <?php
        foreach($images as $imageName)
        {
    ?>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <img src='<?php echo $imageBase.$imageName.$imageExt;?>' class="thumbnail img-thumbnail"/>
            </div>
    <?php
        }
    ?>
</div>
