<?= $this->extend('base_view') ?>

<?= $this->section('content') ?>
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="components-preview wide-md mx-auto">
            <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                    <?= $this->include('layouts/partials/notification') ?>
                    </div>
                </div>
                <div class="card card-bordered card-preview" style="padding:30px;">
                    <div class="card-inner">
                        <h4 class="title nk-block-title">Edit Profile</h4>
                        <div class="preview-block">
                            <form action="/user/update/<?= $data['id'] ?>" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Full Name:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="full_name" class="form-control" required value="<?= $data['full_name'] ?>">
                                                <?= session()->getFlashdata('error_full_name') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Email:</label>
                                            <div class="form-control-wrap">
                                                <input type="email" name="email" class="form-control" required value="<?= $data['email'] ?>">
                                                <?= session()->getFlashdata('error_email') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Username:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="username" class="form-control" required value="<?= $data['username'] ?>">
                                                <?= session()->getFlashdata('error_username') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Password:</label>
                                            <span style="color: red; font-size: 12px; font-weight: normal;margin-left: 20px;">*Fill in the fields below to change the password</span>
                                            <div class="form-control-wrap">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" value="<?= old('password') ?>"/>
                                                <?= session()->getFlashdata('error_password') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Confirm Password:</label>
                                            <span style="color: red; font-size: 12px; font-weight: normal;margin-left: 20px;">*Fill in the fields below to change the confirm password</span>
                                            <div class="form-control-wrap">
                                                <input type="password" id="password_confirm" class="form-control" name="password_confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" value="<?= old('password_confirm') ?>"/>
                                                <?= session()->getFlashdata('error_password_confirm') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Edit User</button>
                                            <a href="#modalDelete" class="btn btn-danger" data-bs-toggle="modal">Delete Account</a>
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
<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-sm">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-body" style="text-align: center;">
                <div class="swal2-header">
                    <i class="menu-icon tf-icons bx bx-error-circle" style="font-size: 75px;"></i>
                    <div class="swal2-icon swal2-info" style="display: none;"></div>
                    <div class="swal2-icon swal2-success" style="display: none;"></div>
                    <img class="swal2-image" style="display: none;">
                    <h2 class="swal2-title" id="swal2-title" style="display: flex;justify-content: center;">Are you sure?</h2>
                    <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
                </div>
                <p>You want to delete your account permanently?</p>
                <div class="swal2-actions">
                    <a href="/user/delete/<?= $data['id'] ?>" type="button" class="btn btn-success" aria-label="" style="display: inline-block; border-left-color: rgb(30, 224, 172) !important; border-right-color: rgb(30, 224, 172) !important;">Yes, delete it!</a>
                    <a href="#" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>