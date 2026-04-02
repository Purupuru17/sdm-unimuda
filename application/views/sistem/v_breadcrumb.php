<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
    <script type="text/javascript">
        try {
            ace.settings.check('breadcrumbs', 'fixed')
        } catch (e) {
        }

    </script>
    <ul class="breadcrumb" style="text-transform: capitalize">
        <li>
            <a href="<?= site_url() ?>"><i class="ace-icon fa fa-home home-icon"></i></a>
        </li>
        <?= breadcrumb($breadcrumb);?>
    </ul>
    <!-- /.breadcrumb -->
</div>
<!-- /section:basics/content.breadcrumbs -->                
