<?php
$this->addPageSpecificCss('upload-contacts.css')
    ->addPageSpecificJs('jquery.ui.widget.js')
    ->addPageSpecificJs('jquery.fileupload.js')
    ->addPageSpecificJs('upload-contacts.js');
?>
<div class="col-lg-12 text-center">
    <form id="upload" method="post" action="contacts/upload" enctype="multipart/form-data">
        <div id="drop">
            Drop Here
            <a>Browse</a>
            <input type="file" name="contacts"/>
        </div>

        <div class="progress hidden">
            <div class="progress-bar" role="progressbar" style="width:0"></div>
        </div>
    </form>
</div>