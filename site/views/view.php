<div style='float:left;'>
    <h1>View All Photos</h1>
</div>

<div style='float: right; text-align: right;'>
    <a href='/picroll/html/upload/index'>Upload a photo</a>
    <br />
    <a href='/picroll/html/login/logout'>Log out</a>
</div>

<div style='clear: both'></div>

<?php
    foreach($images as $imageName)
    {
?>
        <img src='/picroll/images/uploads/<?php echo $imageName;?>'/>
<?php
    }
?>


