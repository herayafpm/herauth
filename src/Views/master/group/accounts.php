<?php $this->extend("{$_main_path}templates/layout") ?>
<?php $this->section('css') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<form @submit.prevent="proses">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div v-if="alertType !== ''" class="alert" :class="'alert-'+alertType">
                    {{messageApi}}
                </div>
                <div class="form-group">
                    <label for="username"><?= lang('Label.username') ?></label>
                    <input type="text" class="form-control" :class="errorsApi.username !== undefined?'is-invalid':''" name="username" v-model="username" placeholder="<?= lang('Label.username') ?>">
                    <div class="invalid-feedback">
                        {{errorsApi.username}}
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <button v-if="!loadingApi" type="submit" class="btn btn-primary btn-block"><?= lang("Label.add") ?></button>
                    <button v-else type="submit" class="btn btn-primary btn-block" disabled>
                        <div class="d-flex align-items-center">
                            <strong><?= lang("Label.adding") ?>...</strong>
                            <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                        </div>
                    </button>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <?= lang("Label.accounts") ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tableAccountGroup" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10"><?= lang("Label.datatable.no") ?></th>
                                    <th><?= lang("Label.username") ?></th>
                                    <th width="100"><?= lang("Label.datatable.action") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center" width="10"><?= lang("Label.datatable.no") ?></th>
                                    <th><?= lang("Label.username") ?></th>
                                    <th width="100"><?= lang("Label.datatable.action") ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="form-check form-check-inline" v-for="(user_group,index) in user_groups">
                    <div>
                        <label :for="'username'+index">{{user_group.username}}</label>
                        <button class="btn btn-sm btn-danger" @click="deleteUserGroup(user_group.username)" type="button">
                            <i class="fas fa-fw fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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
<script>
    dataVue = {
        username: '',
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
        groups: [],
        user_groups: []
    }
    createdVue = function() {
        this.getUserGroups()
    }
    methodsVue = {
        cleanForm() {
            this.errorsApi = {}
            this.dataApi = {}
            this.messageApi = ''
            this.alertType = ''
        },
        async prosesDeleteAccountGroup(username) {
            this.loadingApi = true
            this.cleanForm()

            await axios.post("<?= $url_delete_account_group ?>", {
                username
            }, {
                validateStatus: () => true
            }).then((res) => {
                if (res.status !== 200) {
                    this.alertType = 'danger'
                    this.messageApi = res.data.message ?? 'Error ' + res.status
                    this.errorsApi = res.data.data ?? {}
                } else {
                    this.dataApi = res.data.data
                    if (res.data.status) {
                        this.alertType = 'success'
                        this.messageApi = res.data.message
                        this.getUserGroups()
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        },
        async deleteUserGroup(username) {
            Swal.fire({
                title: herlangjs('Label.confirm')+" "+herlangjs("Label.delete")+" "+username,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: herlangjs('Label.cancel'),
                confirmButtonText: herlangjs('Label.confirm') + ", " + herlangjs('Label.delete') + "!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.prosesDeleteAccountGroup(username)
                }
            })
        },
        async proses() {
            this.loadingApi = true
            this.cleanForm()
            var formData = new FormData()
            formData.append('username', this.username);

            await axios.post("<?= $url_add_account_group ?>", formData, {
                validateStatus: () => true
            }).then((res) => {
                if (res.status !== 200) {
                    this.alertType = 'danger'
                    this.messageApi = res.data.message ?? 'Error ' + res.status
                    this.errorsApi = res.data.data ?? {}
                } else {
                    this.dataApi = res.data.data
                    if (res.data.status) {
                        this.alertType = 'success'
                        this.messageApi = res.data.message
                        this.username = ''
                        this.getUserGroups()
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        },
        async getUserGroups() {
            this.loadingApi = true
            this.cleanForm()

            await axios.get("<?= $url_account_groups ?>", {}, {
                validateStatus: () => true
            }).then((res) => {
                if (res.status !== 200) {
                    this.alertType = 'danger'
                    this.messageApi = res.data.message ?? 'Error ' + res.status
                    this.errorsApi = res.data.data ?? {}
                } else {
                    this.dataApi = res.data.data
                    if (res.data.status) {
                        this.alertType = 'success'
                        this.messageApi = res.data.message

                        var tableAccountGroup = $("#tableAccountGroup").DataTable({
                            "destroy": true,
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
                            "buttons": [{
                                extend: "pageLength",
                                attr: {
                                    "class": "btn btn-primary"
                                },
                            }],
                            "ordering": true, // Set true agar bisa di sorting
                            "order": [
                                [0, 'desc']
                            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                            "autoWidth": false,
                            "lengthMenu": [
                                [10, 25, 50, -1],
                                ['10 ' + herlangjs("Label.datatable.row"), '25 ' + herlangjs("Label.datatable.row"), '50 ' + herlangjs("Label.datatable.row"), herlangjs("Label.datatable.showAll")]
                            ],
                            "data": res.data.data,
                            'columnDefs': [{
                                "targets": [2],
                                "orderable": false
                            }, {
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
                                    "data": "id",
                                    "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
                                        var html = '';
                                        html += `
                                        <a role="button" class="btn btn-sm btn-danger hapusData" data-username="${row.username}">
                                            <i class="fas fa-fw fa-trash"></i>
                                        </a>`
                                        return html
                                    }
                                },
                            ],
                        })
                        tableAccountGroup.on('order.dt page.dt', function() {
                            tableAccountGroup.column(0, {
                                order: 'applied',
                                page: 'applied',
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1;
                            });
                        }).draw();
                        $("#tableAccountGroup").on('click', '.hapusData', function() {
                            var username = $(this).data('username')
                            vue.deleteUserGroup(username)
                        })
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        }
    }
</script>
<?php $this->endSection('js') ?>