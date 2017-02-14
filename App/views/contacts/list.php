<?php
$this->addPageSpecificCss('dataTables.bootstrap.min.css')
    ->addPageSpecificJs('jquery.datatables.min.js')
    ->addPageSpecificJs('DT_bootstrap.js')
    ->addPageSpecificJs('contact-list.js');
?>
<div class="col-md-12">
    <a class="btn btn-default" href="<?php echo $this->getBaseUrl() ?>contacts/import">Import Contacts</a>
</div>
<br /><br /><br />
<div class="col-md-12">
    <table id="contact-list" class="table table-striped table-bordered table-condensed table-hover">
        <thead>
            <tr>
                <th> ID</th>
                <th> First Name</th>
                <th> Last Name</th>
                <th> Phone</th>
                <th> Email</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>