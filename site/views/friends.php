<h3>Friends</h3>

<div class="section left">
    <div class="sectionInner">
        <h2>Your Friends</h2>


        <?php
            if(count($friends) === 0)
            {
        ?>
               <span id="noFriends">You don't have any friends on Picroll yet! </span>
        <?php
            } 
        ?>
        <ul id="friendList">
        <?php
            if(count($friends) > 0)
            {
                foreach($friends as $id => $username)
                {
        ?>
                    <li><?php echo $username;?></li>
        <?php
                }
            }
        ?>
        </ul>

        <?php
            if($requests && count($requests) > 0)
            {
        ?>
                <h3>These people want to be your friend</h3>
        <?php
            }
        ?>

        <ul id="friendRequestList">
            <?php
                if($requests && count($requests) > 0)
                {
                    foreach($requests as $friendId => $friendName)
                    {
            ?>
                        <li data-friendid="<?php echo $friendId;?>" data-friendname="<?php echo $friendName;?>">
                            <div class="left">
                                <?php echo $friendName;?>
                            </div>
                            <div class="right">
                                <button class="btn" data-friendid="<?php echo $friendId;?>">Add</button>
                            </div>
                        </li>
            <?php
                    }
                }
            ?>
        </ul>

        <?php
            if($pending && count($pending) > 0)
            {
        ?>
                <h3>You are waiting for these people to accept your Friend Request</h3>
        <?php
            }
        ?>

        <ul id="pendingFriendList">
            <?php
                if($pending && count($pending) > 0)
                {
                    foreach($pending as $friendId => $friendName)
                    {
            ?>
                        <li data-friendid="<?php echo $friendId;?>" data-friendname="<?php echo $friendName;?>">
                            <div class="left">
                                <?php echo $friendName;?>
                            </div>
                        </li>
            <?php
                    }
                }
            ?>
        </ul>
    </div>
</div>

<div class="section right">
    <div class="sectionInner">
        <h2>Search for friends</h2>

        <input type="text" id="friendSearch" placeholder="Search..."></input>
        <button id="searchSubmit">Submit</button>
        
        <div id="searchResult"></div>
    </div>
</div>
