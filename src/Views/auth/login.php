<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $page_title ?? "Login" ?> | <?= $_app_name ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box" id="appVue">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="<?= herauth_base_url() ?>" class="h1"><?= $_app_name ?></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg"><?=lang("Label.login")?> <?=lang("Label.admin")?></p>

        <form method="post" @submit.prevent="loginProcess">
          <div v-if="alertType !== ''" class="alert" :class="'alert-'+alertType">{{messageApi}}</div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" :class="errorsApi.username !== undefined?'is-invalid':''" placeholder="Username" v-model='username'>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            <div class="invalid-feedback">
              {{errorsApi.username}}
            </div>
          </div>
          <div class="input-group mb-3">
            <input :type="showPass?'text':'password'" class="form-control" :class="errorsApi.password !== undefined?'is-invalid':''" placeholder="Password" v-model="password">
            <div class="input-group-append" @click="showPass = !showPass">
              <div class="input-group-text">
                <span class="fas" :class="showPass?'fa-eye-slash':'fa-eye'"></span>
              </div>
            </div>
            <div class="invalid-feedback">
              {{errorsApi.password}}
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button v-if="!loadingApi" type="submit" class="btn btn-primary btn-block"><?=lang("Label.login")?></button>
              <button v-else type="submit" class="btn btn-primary btn-block" disabled>
                <div class="d-flex align-items-center">
                  <strong><?=lang("Label.login")?>...</strong>
                  <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                </div>
              </button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= herauth_asset_url('vendor/adminlte') ?>/dist/js/adminlte.min.js"></script>
  <!-- axios -->
  <script src="<?= herauth_asset_url('vendor/axios') ?>/axios.min.js"></script>
  <script src="<?= herauth_asset_url('vendor/vuejs') ?>/vue.min.js"></script>
  <script>
    var vue = new Vue({
      el: '#appVue',
      data: {
        username: '',
        password: '',
        showPass: false,
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
      },
      methods: {
        cleanForm() {
          this.errorsApi = {}
          this.dataApi = {}
          this.messageApi = ''
          this.alertType = ''
        },

        async loginProcess() {
          this.loadingApi = true
          this.cleanForm()
          var formData = new FormData()
          formData.append('username', this.username);
          formData.append('password', this.password);

          await axios.post("<?= herauth_web_url('auth/login') ?>", formData, {
            validateStatus: () => true}).then((res) => {
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
                  window.location.href = res.data.data.redir;
                }, 2000);
              }
            }
          })
          this.loadingApi = false
        }
      }
    })
  </script>
</body>

</html>