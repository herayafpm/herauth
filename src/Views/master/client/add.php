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
                    <label for="name"><?= lang('Label.name') ?></label>
                    <input type="text" class="form-control" :class="errorsApi.name !== undefined?'is-invalid':''" name="name" v-model="name" placeholder="<?= lang('Label.name') ?>">
                    <div class="invalid-feedback">
                        {{errorsApi.name}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="expired"><?= lang('Label.client.expired') ?> (<?= lang('Label.optional') ?>)</label>
                    <input type="date" class="form-control" :class="errorsApi.expired !== undefined?'is-invalid':''" name="expired" v-model="expired" placeholder="<?= lang('Label.client.expired') ?> (<?= lang('Label.optional') ?>)">
                    <div class="invalid-feedback">
                        {{errorsApi.expired}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="hit_limit"><?= lang('Label.client.hit_limit') ?> (<?= lang('Label.optional') ?>)</label>
                    <input type="number" min="0" class="form-control" :class="errorsApi.hit_limit !== undefined?'is-invalid':''" name="hit_limit" v-model="hit_limit" placeholder="<?= lang('Label.client.hit_limit') ?> (<?= lang('Label.optional') ?>)">
                    <div class="invalid-feedback">
                        {{errorsApi.hit_limit}}
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
        name: "",
        expired: "",
        hit_limit: "",
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
        async proses() {
            this.loadingApi = true
            this.cleanForm()
            var formData = new FormData()
            formData.append('name', this.name);
            formData.append('expired', this.expired);
            formData.append('hit_limit', this.hit_limit);

            await axios.post("<?= $url_add ?>", formData, {
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
                            window.location.href = res.data.data.redir
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