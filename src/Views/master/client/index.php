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
                <a role="button" class="btn btn-sm btn-success" href="<?= $url_add ?>"><?= lang("Label.add") . " " . lang("Label.client.text") ?></a>
            </div>
            <div class="card-body">
                <table id="tableMaster" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10"><?= lang("Label.datatable.no") ?></th>
                            <th><?= lang("Label.name") ?></th>
                            <th><?= lang("Label.client.expired") ?></th>
                            <th><?= lang("Label.client.hitLimit") ?></th>
                            <th><?= lang("Label.datatable.updatedAt") ?></th>
                            <th width="100"><?= lang("Label.datatable.action") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" width="10"><?= lang("Label.datatable.no") ?></th>
                            <th><?= lang("Label.name") ?></th>
                            <th><?= lang("Label.client.expired") ?></th>
                            <th><?= lang("Label.client.hitLimit") ?></th>
                            <th><?= lang("Label.datatable.updatedAt") ?></th>
                            <th width="100"><?= lang("Label.datatable.action") ?></th>
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
    var tableMaster = null;

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
            tableMaster.ajax.reload(function(json) {
                vue.list = json.data
            })
        },
    }


    async function hapusData(id) {
        await axiosValid.post("<?= $url_delete ?>" + id).then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            vue.reloadDatatable()
        })
    }
    async function purgeData(id) {
        await axiosValid.post("<?= $url_delete ?>" + id + "?purge=1").then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            vue.reloadDatatable()
        })
    }
    async function restoreData(id) {
        await axiosValid.post("<?= $url_restore ?>" + id).then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            vue.reloadDatatable()
        })
    }
    async function regenerateKey(id) {
        await axiosValid.post("<?= $url_regenerate_key ?>" + id).then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            vue.reloadDatatable()
        })
    }

    $(document).ready(function() {
        tableMaster = $("#tableMaster").DataTable({
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
                ['10 ' + herlangjs("Label.datatable.row"), '25 ' + herlangjs("Label.datatable.row"), '50 ' + herlangjs("Label.datatable.row"), herlangjs("Label.datatable.showAll")]
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
                "targets": [4],
                "orderable": false
            }, {
                "targets": [0],
                "className": 'text-center'
            }],
            "columns": [{
                    "data": "id",
                },
                {
                    "data": "name",
                },
                {
                    "data": "expired",
                    "render": function(dt, type, row, meta) {
                        return row.expired === null ? '-' : toLocaleDate(row.expired.date, 'LL');
                    }
                },
                {
                    "data": "hit_limit",
                    "render": function(dt, type, row, meta) {
                        return row.hit_limit === null ? '-' : formatRupiah(row.hit_limit);
                    }
                },
                {
                    "data": "updated_at",
                    "render": function(dt, type, row, meta) {
                        return row.updated_at === null ? '-' : toLocaleDate(row.updated_at.date, 'LLL');
                    }
                },
                {
                    "data": "id",
                    "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
                        var html = '';
                        html += `
                            <a role="button" class="btn btn-sm btn-info" href="<?= $url_permissions ?>${row.id}">
                                <i class="fas fa-fw fa-lock"></i>
                            </a>
                            `
                        html += `
                            <a role="button" class="btn btn-sm btn-info" href="<?= $url_whitelists ?>${row.id}">
                                <i class="fab fa-fw fa-android"></i>
                            </a>
                            `
                        html += `
                            <a role="button" class="btn btn-sm btn-primary" href="<?= $url_edit ?>${row.id}">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>
                            `
                        html += `
                            <a role="button" class="btn btn-sm btn-warning regenerateData" data-id="${row.id}">
                                <i class="fas fa-fw fa-sync"></i>
                            </a>
                            `
                        html += `
                            <a role="button" class="btn btn-sm btn-info showClientKey" data-id="${meta.row}">
                                <i class="fas fa-fw fa-eye"></i>
                            </a>
                            `
                        if (row.deleted_at === null) {
                            html += `
                            <a role="button" class="btn btn-sm btn-danger hapusData" data-id="${row.id}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                        } else {
                            html += `
                            <a role="button" class="btn btn-sm btn-info restoreData" data-id="${row.id}">
                                <i class="fas fa-fw fa-recycle"></i>
                            </a>
                            <a role="button" class="btn btn-sm btn-danger purgeData" data-id="${row.id}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                        }
                        return html
                    }
                },
            ],
        });
        tableMaster.on('order.dt page.dt', function() {
            tableMaster.column(0, {
                order: 'applied',
                page: 'applied',
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
        $("#tableMaster").on('click', '.regenerateData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: herlangjs('Label.confirm')+" "+herlangjs('Label.client.regenerateKey')+" "+herlangjs('Label.client.text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: herlangjs('Label.cancel'),
                confirmButtonText: herlangjs('Label.confirm') + ", " + herlangjs('Label.client.regenerateKey') + "!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    regenerateKey(id)
                }
            })
        })
        $("#tableMaster").on('click', '.showClientKey', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: herlangjs('Label.client.text') + " " + herlangjs('Label.client.key'),
                text: vue.list[id].client_key,
                icon: 'info',
            })
        })
        $("#tableMaster").on('click', '.hapusData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: herlangjs('Label.confirm') +" "+herlangjs("Label.delete")+" "+herlangjs('Label.client.text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: herlangjs('Label.cancel'),
                confirmButtonText: herlangjs('Label.confirm') + ", " + herlangjs('Label.delete') + "!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    hapusData(id)
                }
            })
        })
        $("#tableMaster").on('click', '.restoreData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: herlangjs('Label.confirm')+" "+herlangjs("Label.restore")+" "+herlangjs('Label.client.text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: herlangjs('Label.cancel'),
                confirmButtonText: herlangjs('Label.confirm') + ", " + herlangjs('Label.restore') + "!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    restoreData(id)
                }
            })
        })
        $("#tableMaster").on('click', '.purgeData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: herlangjs('Label.confirm')+" "+herlangjs("Label.purge")+" "+herlangjs('Label.client.text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: herlangjs('Label.cancel'),
                confirmButtonText: herlangjs('Label.confirm') + ", " + herlangjs('Label.purge') + "!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    purgeData(id)
                }
            })
        })
    })
</script>
<?php $this->endSection('js') ?>