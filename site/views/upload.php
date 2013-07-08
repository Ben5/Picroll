<?php $this->AddScript('exif.js'); ?>
<?php $this->AddScript('binaryajax.js'); ?>
<h3>Upload Pictures</h3>

<form id='frmUploadPhoto'>
    <input type='file' id='photoUpload' name='photoUpload' multiple='multiple'></input>

    <button id='submit'>Upload Photos</button>
</form>

<div id='previewContainer'></div>
