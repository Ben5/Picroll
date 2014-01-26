<div class="container">
    <div class="col-xs-12">
        <h3>Albums</h3>
    </div>
</div>

<div class="container">
    <div class="col-xs-12 well well-sm">
        <div class="btn-group">
            <button id="btnSelectAlbums" class="btn btn-default">Select Albums</button>
        </div>
        <div class="btn-group">
            <button id="btnDeleteSelectedAlbums" class="btn btn-danger">Delete <span class="glyphicon glyphicon-trash"></span></button>
        </div>
        <div id="albumNotificationArea" class="pull-right">
        </div>
        <div class="clearfix">
        </div>
    </div>
</div>

<div id="allAlbumsContainer" class="container">
    <?php
        $count  = 0;

        $perRowSmall = 2;
        $perRowMed   = 4;
        $perRowLarge = 6;

        foreach ($albums as $albumId => $album) {
            $count++;
            $imageName = reset($album['images']);
            $thumbImg = $imageBase.$imageName.'-thumb'.$imageExt;

    ?>
            <div class="thumbnailContainer col-xs-12 col-sm-6 col-md-3 col-lg-2">
                <img src="<?php echo $thumbImg;?>" class="thumbnail img-thumbnail" data-albumid="<?php echo $album['id'];?>"/>
                <span class="label <?php echo $album['id'] > 0 ? 'label-info' : 'label-primary';?> albumTitle">
                    <?php echo $album['name'];?>
                </span>
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

<div class="container">
    <div class="col-xs-12">
        <h3>Pictures</h3>
    </div>
</div>

<div class="container">
    <div class="col-xs-12 well well-sm">
        <div class="btn-group">
            <button id="btnSelectPictures" class="btn btn-default">Select Pictures</button>
        </div>
        <div class="btn-group">
            <button id="btnDeleteSelected" class="btn btn-danger">Delete <span class="glyphicon glyphicon-trash"></span></button>
            <button id="btnRemoveSelected" class="btn btn-warning">Remove From Album <span class="glyphicon glyphicon-remove-circle"></span></button>
            <div id="btnAddSelectedToAlbum" class="btn-group">
                <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    Add To Another Album <span class="glyphicon glyphicon-book"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach ($albums as $album): ?>
                    <?php if ($album['id'] == -1) { continue; } ?>
                        <li>
                            <a href='#' class="existingAlbum" data-albumid="<?php echo $album['id']; ?>">
                                <?php echo $album['name']; ?> (<?php echo $album['size'];?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="divider"></li>
                    <li><a href='#' id="createNewAlbum">Create New Album</a></li>
                </ul>
            </div>
        </div>
        <div id="pictureNotificationArea" class="pull-right">
        </div>
        <div class="btn-group">
            <button id="btnViewAsSlideshow" class="btn btn-default">View Album As Slideshow</button>
        </div>

        <div class="clearfix">
        </div>
    </div>
</div>

<div id="allThumbnailsContainer" class="container">
</div>

<div id="fullscreenImageWrapper">
    <div id="fullscreenImage">
    </div>
</div>

<div class="modal-backdrop">
</div>

<div id="notificationTemplate" class="alert">
</div;>


<!-- template for image thumbnails -->
<div id="thumbnailTemplate">
    <div class="thumbnailContainer col-xs-12 col-sm-6 col-md-3 col-lg-2">
        <img class="thumbnail img-thumbnail" data-imageid="###" data-fullimgsrc="###"/>
        <div class="overlay">
            <span class="glyphicon glyphicon-check" style="display:none"></span>
            <span class="glyphicon glyphicon-unchecked"></span>
        </div>
    </div>
</div>
