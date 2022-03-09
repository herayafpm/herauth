<?php $this->extend("{$_main_path}templates/layout") ?>
<?php $this->section('css') ?>
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<form @submit.prevent="proses">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div v-if="alertType !== ''" class="alert" :class="'alert-'+alertType">
                    {{messageApi}}
                </div>
                <div class="form-group row m-2">
                    <div class="custom-control custom-checkbox custom-control-inline col-md-3" v-for="(permission,index) in permissions">
                        <input type="checkbox" class="custom-control-input" :id="'permissionsCheck'+index" :checked="permission.checked" v-model="permission.checked">
                        <label class="custom-control-label" :for="'permissionsCheck'+index">{{permission.name}}</label>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-5">
                <button v-if="!loadingApi" type="submit" class="btn btn-primary btn-block"><?= lang("Label.save") ?></button>
                <button v-else type="submit" class="btn btn-primary btn-block" disabled>
                    <div class="d-flex align-items-center">
                        <strong><?= lang("Label.saving") ?>...</strong>
                        <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</form>
<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<script>
    dataVue = {
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
        permissions: [],
        group_permissions: []
    }
    createdVue = async function() {
        await this.getGroupPermissions()
        this.getPermissions()
    }
    methodsVue = {
        cleanForm() {
            this.errorsApi = {}
            this.dataApi = {}
            this.messageApi = ''
            this.alertType = ''
        },
        async proses() {
            this.loadingApi = true
            this.cleanForm()

            await axios.post("<?= $url_save ?>", {
                permissions: this.permissions
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
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        },
        async getPermissions() {
            this.loadingApi = true
            this.cleanForm()

            await axios.get("<?= $url_permissions ?>", {}, {
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
                        var data = res.data.data.map((el) => {
                            el.checked = false
                            if (this.group_permissions.filter((elag) => elag.permission_id == el.id).length > 0) {
                                el.checked = true
                            }
                            return el
                        })
                        this.permissions = data
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        },
        async getGroupPermissions() {
            this.loadingApi = true
            this.cleanForm()

            await axios.get("<?= $url_group_permissions ?>", {}, {
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
                        this.group_permissions = res.data.data
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        }
    }
</script>
<?php $this->endSection('js') ?>