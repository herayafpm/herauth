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
                    <label for="description"><?= lang('Label.description') ?> (<?= lang('Label.optional') ?>)</label>
                    <textarea class="form-control" :class="errorsApi.description !== undefined?'is-invalid':''" name="description" v-model="description" placeholder="<?= lang('Label.description') ?> (<?= lang('Label.optional') ?>)" rows="5">
                    </textarea>
                    <div class="invalid-feedback">
                        {{errorsApi.description}}
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="mustLoginCheck" :class="errorsApi.must_login !== undefined?'is-invalid':''" :checked="must_login" v-model="must_login">
                        <label class="custom-control-label" for="mustLoginCheck"><?= lang("Label.mustLogin") ?>?</label>
                        <br />
                        <div class="invalid-feedback">
                            {{errorsApi.must_login}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-5">
                <button v-if="!loadingApi" type="submit" class="btn btn-primary btn-block"><?= lang('Label.save') ?></button>
                <button v-else type="submit" class="btn btn-primary btn-block" disabled>
                    <div class="d-flex align-items-center">
                        <strong><?= lang('Label.saving') ?>...</strong>
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
        name: "<?= $permission->name ?? '' ?>",
        description: `<?= $permission->description ?? '' ?>`,
        must_login: <?= $permission->must_login ?> == 1 ? true : false,
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
            formData.append('description', this.description);
            formData.append('must_login', this.must_login ? 1: 0);

            await axios.post("<?= $url_edit ?>", formData, {
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