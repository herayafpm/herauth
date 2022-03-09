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
            <div class="card-header">
                <?=lang("Label.requestLog")?>
            </div>
            <div class="card-body">
                <table id="tableRequestLog" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10"><?=lang("Label.datatable.no")?></th>
                            <th><?=lang("Label.username")?></th>
                            <th><?=lang("Label.client.text")?></th>
                            <th><?=lang("Label.path")?></th>
                            <th><?=lang("Label.client.ipAddress")?></th>
                            <th><?=lang("Label.statusCode")?></th>
                            <th><?=lang("Label.statusMessage")?></th>
                            <th><?=lang("Label.datatable.createdAt")?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" width="10"><?=lang("Label.datatable.no")?></th>
                            <th><?=lang("Label.username")?></th>
                            <th><?=lang("Label.client.text")?></th>
                            <th><?=lang("Label.path")?></th>
                            <th><?=lang("Label.client.ipAddress")?></th>
                            <th><?=lang("Label.statusCode")?></th>
                            <th><?=lang("Label.statusMessage")?></th>
                            <th><?=lang("Label.datatable.createdAt")?></th>
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
        params: {
            today: 1
        },
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
                        "_": herlangjs("Label.datatable.show") + " %d " + herlangjs("Label.datatable.row") + " <i class='fas fa-fw fa-caret-down'></i>",
                        "-1": herlangjs("Label.datatable.showAll") + " <i class='fas fa-fw fa-caret-down'></i>"
                    }
                },
                "lengthMenu": herlangjs("Label.datatable.show") + " _MENU_ " + herlangjs("Label.datatable.data") + " " + herlangjs("Label.datatable.per") + " " + herlangjs("Label.datatable.page"),
                "zeroRecords": herlangjs("Label.datatable.data") + " " + herlangjs("Label.notFound"),
                "info": herlangjs("Label.datatable.show") + " " + herlangjs("Label.datatable.page") + " _PAGE_ " + herlangjs("Label.datatable.from") + " _PAGES_",
                "infoEmpty": herlangjs("Label.datatable.data") + " " + herlangjs("Label.empty"),
                "infoFiltered": "(" + herlangjs("Label.datatable.di") + herlangjs("Label.datatable.filter") + " " + herlangjs("Label.datatable.from") + " _MAX_ " + herlangjs("Label.datatable.total") + " " + herlangjs("Label.datatable.data") + ")"
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
                ['10 ' + herlangjs("Label.row"), '25 ' + herlangjs("Label.row"), '50 ' + herlangjs("Label.row"), herlangjs("Label.showAll")]
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