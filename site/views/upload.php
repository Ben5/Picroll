<?php $this->AddScript('exif.js'); ?>
<?php $this->AddScript('binaryajax.js'); ?>

<div class="container">
    <div class="col-xs-12">
        <h3>Upload Pictures</h3>
    </div>
</div>

<div class="container">
    <form class="well" id="frmUploadPhoto">
        <div class="form-group">
            <label> 
                Choose Images
                <input type='file' id='photoUpload' name='photoUpload' multiple='multiple'></input>
            </label>
        </div>

        <div id='previewContainer' class="container">
        </div>

        <div class="pull-right">
            <button class="btn btn-primary" id='submit'>Upload Photos</button>
        </div>
        <div class="clearfix" />
    </form>
</div>

