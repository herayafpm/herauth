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
                <div class="form-group">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <?= lang('Label.client.ipAddress') ?>
                        </div>
                        <div class="col-md-6" v-for="(whitelist,index) in whitelist_ips">
                            <div class="form-group row">
                                <input type="text" class="form-control form-control-sm col-md-6" v-model="whitelist.whitelist_name" placeholder="<?= lang('Label.name') ?> <?= lang('Label.client.ipAddress') ?>">
                                <div class="input-group col-md-6">
                                    <input type="text" class="form-control form-control-sm" v-model="whitelist.whitelist_key" placeholder="<?= lang('Label.client.ipAddress') ?>">
                                    <div class="input-group-prepend" style="cursor: pointer;" @click="deleteIpAddress(index)">
                                        <div class="input-group-text bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-sm" @click="addIpAddress()"><?= lang('Label.add') ?> <?= lang('Label.client.ipAddress') ?></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <?= lang('Label.client.android') ?>
                        </div>
                        <div class="col-md-6" v-for="(whitelist,index) in whitelist_androids">
                            <div class="form-group row">
                                <input type="text" class="form-control form-control-sm col-md-6" v-model="whitelist.whitelist_name" placeholder="<?= lang('Label.name') ?> <?= lang('Label.client.android') ?>">
                                <div class="input-group col-md-6">
                                    <input type="text" class="form-control form-control-sm" v-model="whitelist.whitelist_key" placeholder="<?= lang('Label.client.android') ?>">
                                    <div class="input-group-prepend" style="cursor: pointer;" @click="deleteAndroid(index)">
                                        <div class="input-group-text bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-sm" @click="addAndroid()"><?= lang('Label.add') ?> <?= lang('Label.client.android') ?></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <?= lang('Label.client.ios') ?>
                        </div>
                        <div class="col-md-6" v-for="(whitelist,index) in whitelist_ioss">
                            <div class="form-group row">
                                <input type="text" class="form-control form-control-sm col-md-6" v-model="whitelist.whitelist_name" placeholder="<?= lang('Label.name') ?> <?= lang('Label.client.ios') ?>">
                                <div class="input-group col-md-6">
                                    <input type="text" class="form-control form-control-sm" v-model="whitelist.whitelist_key" placeholder="<?= lang('Label.client.ios') ?>">
                                    <div class="input-group-prepend" style="cursor: pointer;" @click="deleteIos(index)">
                                        <div class="input-group-text bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-sm" @click="addIos()"><?= lang('Label.add') ?> <?= lang('Label.client.ios') ?></button>
                        </div>
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
        whitelist_ips: <?= json_encode($client->client_whitelist_web) ?>,
        whitelist_androids: <?= json_encode($client->client_whitelist_android) ?>,
        whitelist_ioss: <?= json_encode($client->client_whitelist_ios) ?>,
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
    }

    methodsVue = {
        cleanForm() {
            this.errorsApi = {}
            this.dataApi = {}
            this.messageApi = ''
            this.alertType = ''
        },
        addIpAddress() {
            this.whitelist_ips.push({
                id: 0,
                whitelist_name: '',
                whitelist_key: '',
            })
        },
        deleteIpAddress(index) {
            this.whitelist_ips.splice(index, 1)
        },
        addAndroid() {
            this.whitelist_androids.push({
                id: 0,
                whitelist_name: '',
                whitelist_key: '',
            })
        },
        deleteAndroid(index) {
            this.whitelist_androids.splice(index, 1)
        },
        addIos() {
            this.whitelist_ioss.push({
                id: 0,
                whitelist_name: '',
                whitelist_key: '',
            })
        },
        deleteIos(index) {
            this.whitelist_ioss.splice(index, 1)
        },
        async proses() {
            this.loadingApi = true
            this.cleanForm()
            var formData = new FormData()
            formData.append('web', this.whitelist_ips);
            formData.append('android', this.whitelist_androids);
            formData.append('ios', this.whitelist_ioss);

            await axios.post("<?= $url_save ?>", {
                web: this.whitelist_ips,
                android: this.whitelist_androids,
                ios: this.whitelist_ioss,
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
                        setTimeout(() => {
                            // window.location.reload()
                        }, 1000);
                    }
                }
                window.scrollTo(0, 0)
            })
            this.loadingApi = false
        }
    }
</script>
<?php $this->endSection('js') ?>