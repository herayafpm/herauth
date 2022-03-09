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
                    <label for="username"><?= lang('Auth.labelUsername') ?></label>
                    <input type="text" class="form-control" :class="errorsApi.username !== undefined?'is-invalid':''" name="username" v-model="username" placeholder="<?= lang('Auth.labelUsername') ?>">
                    <div class="invalid-feedback">
                        {{errorsApi.username}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="password"><?= lang('Auth.labelPassword') ?></label>
                    <div class="input-group mb-3">
                        <input :type="showPass?'text':'password'" class="form-control" :class="errorsApi.password !== undefined?'is-invalid':''" placeholder="<?= lang('Auth.labelPassword') ?>" v-model="password">
                        <div class="input-group-append" @click="showPass = !showPass">
                            <div class="input-group-text">
                                <span class="fas" :class="showPass?'fa-eye-slash':'fa-eye'"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            {{errorsApi.password}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-5">
                <button v-if="!loadingApi" type="submit" class="btn btn-primary btn-block"><?= lang("Web.save") ?></button>
                <button v-else type="submit" class="btn btn-primary btn-block" disabled>
                    <div class="d-flex align-items-center">
                        <strong><?= lang("Web.saving") ?>...</strong>
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
        username: "",
        password: "",
        showPass: false,
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
            formData.append('username', this.username);
            formData.append('password', this.password);

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