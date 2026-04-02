<div class="col-xs-12">
    <label>Pilih Tanggal</label>
    <div class="row">
        <form id="grafik-form" method="POST">
            <div class="col-sm-4">
                <div class="input-daterange input-group">
                    <input required="" type="text" class="form-control" name="awal" id="awal" placeholder="Tanggal Awal" />
                    <span class="input-group-addon">
                        <i class="fa fa-exchange"></i>
                    </span>
                    <input required="" type="text" class="form-control" name="akhir" id="akhir" placeholder="Tanggal Akhir"/>
                </div>
            </div>
            <div class="col-sm-4">
                <button type="button" id="btn-search" class="btn btn-white btn-primary btn-bold">
                    <i class="fa fa-search-plus blue"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
    <p id="one-spin" class="bigger-130 blue" style="display: none" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title bigger lighter orange">
                <i class="ace-icon fa fa-bar-chart"></i>
                Grafik Pengunjung
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main table-responsive">
                <div id="container" style="height: 400px; min-width: 380px"></div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-users"></i>
                Pengunjung Website [ <strong id="txt-total"></strong> ]
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main padding-2 table-responsive">
                <table id="index-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>IP</th>
                            <th>x Day</th>
                            <th width="30%">Request URL</th>
                            <th width="20%">Referrer Page</th>
                            <th>Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->  