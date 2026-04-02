<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[0] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[1] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">User :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input type="hidden" name="user" id="user" class="width-100"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Subject :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= ctk($notif['subject_notif']) ?>" type="text" name="subject" id="subject" class="col-xs-12  col-sm-6" placeholder="Subject" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pesan :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea cols="1" rows="5" name="pesan" id="pesan" class="col-xs-12  col-sm-6" placeholder="Pesan"><?= ctk($notif['msg_notif']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Link :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= ctk($notif['link_notif']) ?>" type="text" name="link" id="link" class="col-xs-12  col-sm-6" placeholder="Link" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-4">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-check"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php load_js(array(
    "theme/aceadmin/assets/js/jquery.validate.js",
    "theme/aceadmin/assets/js/select2.js"
)); ?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        validate_form();
    });
    function validate_form() {
        jsfValidate("#validation-form", {
            user: {
                required: true
            },
            subject: {
                required: true
            },
            pesan: {
                required: true
            },
            link: {
                required: true
            }
        });
    }
</script>                  
