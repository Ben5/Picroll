<h3>View Pictures</h3>

<?php
    foreach($images as $imageName)
    {
?>
        <img src='<?php echo $imageBase.$imageName.$imageExt;?>'/>
<?php
    }
?>


