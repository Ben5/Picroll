<h3>View Pictures</h3>

<?php
    foreach($images as $image)
    {
?>
        <img src='<?php echo $imageBase.$image['filename'].$imageExt;?>'/>
<?php
    }
?>


