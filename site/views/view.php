<h3>View Pictures</h3>

<div class="container">
    <ul class="list-unstyled list-inline">
    <?php
        foreach($images as $imageName)
        {
    ?>
            <li>
                <img src='<?php echo $imageBase.$imageName.$imageExt;?>' class="thumbnail img-thumbnail"/>
            </li>
    <?php
        }
    ?>
    </ul>
</div>
