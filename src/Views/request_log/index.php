<?php $this->extend("{$_main_path}templates/layout") ?>
<?php $this->section('css') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="tableRequestLog" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10"><?=lang("Web.datatable.no")?></th>
                            <th><?=lang("Auth.labelUsername")?></th>
                            <th><?=lang("Web.master.client")?></th>
                            <th><?=lang("Web.master.path")?></th>
                            <th><?=lang("Web.client.ipAddress")?></th>
                            <th><?=lang("Web.statusCode")?></th>
                            <th><?=lang("Web.statusMessage")?></th>
                            <th><?=lang("Web.datatable.createdAt")?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" width="10"><?=lang("Web.datatable.no")?></th>
                            <th><?=lang("Auth.labelUsername")?></th>
                            <th><?=lang("Web.master.client")?></th>
                            <th><?=lang("Web.master.path")?></th>
                            <th><?=lang("Web.client.ipAddress")?></th>
                            <th><?=lang("Web.statusCode")?></th>
                            <th><?=lang("Web.statusMessage")?></th>
                            <th><?=lang("Web.datatable.createdAt")?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<!-- DataTables  & Plugins -->
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    var tableRequestLog = null;

    dataVue = {
        list: [],
        params: {},
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
    }

    methodsVue = {
        reloadDatatable: function() {
            tableRequestLog.ajax.reload(function(json) {
                vue.list = json.data
            })
        },
    }

    $(document).ready(function() {
        tableRequestLog = $("#tableRequestLog").DataTable({
            "responsive": true,
            "language": {
                "buttons": {
                    "pageLength": {
                        "_": herlangjs("Web.datatable.show") + " %d " + herlangjs("Web.datatable.row") + " <i class='fas fa-fw fa-caret-down'></i>",
                        "-1": herlangjs("Web.datatable.showAll") + " <i class='fas fa-fw fa-caret-down'></i>"
                    }
                },
                "lengthMenu": herlangjs("Web.datatable.show") + " _MENU_ " + herlangjs("Web.datatable.data") + " " + herlangjs("Web.datatable.per") + " " + herlangjs("Web.datatable.page"),
                "zeroRecords": herlangjs("Web.datatable.data") + " " + herlangjs("Web.notFound"),
                "info": herlangjs("Web.datatable.show") + " " + herlangjs("Web.datatable.page") + " _PAGE_ " + herlangjs("Web.datatable.from") + " _PAGES_",
                "infoEmpty": herlangjs("Web.datatable.data") + " " + herlangjs("Web.empty"),
                "infoFiltered": "(" + herlangjs("Web.datatable.di") + herlangjs("Web.datatable.filter") + " " + herlangjs("Web.datatable.from") + " _MAX_ " + herlangjs("Web.datatable.total") + " " + herlangjs("Web.datatable.data") + ")"
            },
            "dom": 'Bfrtip',
            "buttons": [
                "copy", "csv", "excel", "pdf", "print", "colvis", {
                    extend: "pageLength",
                    attr: {
                        "class": "btn btn-primary"
                    },
                }
            ],
            "searching": true,
            "processing": true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [0, 'desc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10 ' + herlangjs("Web.datatable.row"), '25 ' + herlangjs("Web.datatable.row"), '50 ' + herlangjs("Web.datatable.row"), herlangjs("Web.datatable.showAll")]
            ],
            "ajax": {
                "url": "<?= $url_datatable ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    return {
                        ...d,
                        ...vue.params
                    }
                }
            },
            "initComplete": function(settings, json) {
                vue.list = json.data;
            },
            'columnDefs': [{
                "targets": [0],
                "className": 'text-center'
            }],
            "columns": [{
                    "data": "id",
                },
                {
                    "data": "username",
                },
                {
                    "data": "client",
                },
                {
                    "data": "path",
                },
                {
                    "data": "ip",
                },
                {
                    "data": "status_code",
                },
                {
                    "data": "status_message",
                },
                {
                    "data": "created_at",
                    "render": function(dt, type, row, meta) {
                        return toLocaleDate(row.created_at.date, 'LLL');
                    }
                }
            ],
        });
        tableRequestLog.on('order.dt page.dt', function() {
            tableRequestLog.column(0, {
                order: 'applied',
                page: 'applied',
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    })
</script>
<?php $this->endSection('js') ?>