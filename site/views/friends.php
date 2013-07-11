<h3>Friends</h3>

<?php
    if(count($friends) === 0)
    {
?>
       You don't have any friends yet! 
<?php
    } 
    else 
    {
?>
        <ul>
        <?php
            foreach($friends as $friend)
            {
        ?>
                <li><?php echo $friend;?></li>
        <?php
            }
        ?>
        </ul>
<?php
    }
?>
