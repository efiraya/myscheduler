<?= $this->extend('base_view') ?>

<?= $this->section('content') ?>
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="components-preview wide-md mx-auto">
            <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <?= $this->renderSection('layouts.partials.notification') ?>
                    </div>
                </div>
                <div class="card card-bordered card-preview" style="padding:30px;">
                    <div class="card-inner">
                        <h4 class="title nk-block-title">Input New User</h4>
                        <div class="preview-block">
                            <form action="/user/submit" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Full Name:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="full_name" class="form-control" required value="<?= old('full_name') ?>">
                                                <?= session()->getFlashdata('error_full_name') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Email:</label>
                                            <div class="form-control-wrap">
                                                <input type="email" name="email" class="form-control" required value="<?= old('email') ?>">
                                                <?= session()->getFlashdata('error_email') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Username:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="username" class="form-control" required value="<?= old('username') ?>">
                                                <?= session()->getFlashdata('error_username') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Password:</label>
                                            <div class="form-control-wrap">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required value="<?= old('password') ?>"/>
                                                <?= session()->getFlashdata('error_password') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Description:</label>
                                            <div class="form-control-wrap">
                                                <input type="password" id="password_confirm" class="form-control" name="password_confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required value="<?= old('password_confirm') ?>"/>
                                                <?= session()->getFlashdata('error_password_confirm') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Add User</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            document.getElementById('length').style.color = password.length >= 8 ? 'green' : '';
            document.getElementById('uppercase').style.color = /[A-Z]/.test(password) ? 'green' : '';
            document.getElementById('lowercase').style.color = /[a-z]/.test(password) ? 'green' : '';
            document.getElementById('number').style.color = /\d/.test(password) ? 'green' : '';
            document.getElementById('special').style.color = /[!@#$%^&*(),.?":{}|<>]/.test(password) ? 'green' : '';
        });
    </script>
<?= $this->endSection() ?>