<!DOCTYPE html>

<html lang="en" class="light-style   layout-menu-fixed     customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/" data-base-url="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1" data-framework="laravel" data-template="blank-menu-theme-default-light">


<!-- Mirrored from demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/login-basic by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Nov 2023 02:06:18 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login</title>
    <meta name="description" content="Most Powerful &amp; Comprehensive Bootstrap 5 HTML Admin Dashboard Template built for developers!" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="MYGKgtXso8bPbF1SgZ3fYRD2FCsbt819LvDCES9p">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://themeselection.com/item/sneat-bootstrap-laravel-admin-template/">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/fonts/boxiconsc4a7.css?id=87122b3a3900320673311cebdeb618da ') ?>" />
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/fonts/fontawesome8a69.css?id=a2997cb6a1c98cc3c85f4c99cdea95b5 ') ?>" />
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/fonts/flag-icons80a8.css?id=121bcc3078c6c2f608037fb9ca8bce8d ') ?>" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/css/rtl/corea8ac.css?id=55b2a9dfaa009c41df62ca8d16e913a8" class="template-customizer-core-css ') ?>" />
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/css/rtl/theme-default4c4b.css?id=9182924a7b965439eca5e189ba43eba1" class="template-customizer-theme-css ') ?>" />
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/css/demob77a.css?id=69dfc5e48fce5a4ff55ff7b593cdf93d ') ?>" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/libs/perfect-scrollbar/perfect-scrollbare482.css?id=73d641bb8db2475ecafc6c68461ed01b ') ?>" />
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/libs/typeahead-js/typeahead05d2.css?id=de339ead5e9c9e36f12e46cbef7aaffd ') ?>" />

    <!-- Vendor Styles -->
    <!-- Vendor -->
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/libs/%40form-validation/umd/styles/index.min.css ') ?>" />


    <!-- Page Styles -->
    <!-- Page -->
    <link rel="stylesheet" href="<?= base_url('demo-1/demo/assets/vendor/css/pages/page-auth.css ') ?>">

    <!-- laravel style -->
    <script src="<?= base_url('demo-1/demo/assets/vendor/js/helpers.js ') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/js/template-customizer.js ') ?>"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url('demo-1/demo/assets/js/config.js ') ?>"></script>

</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <p><?= esc($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <h4 class="mb-4" style="text-align: -webkit-center;">MY SCHEDULER</h4>
                        <p class="mb-4" style="text-align: -webkit-center;">REGISTER</p>

                        <form action="<?= base_url('auth/registerSubmit') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="full_name" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>

                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Confirm Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirm" class="form-control" name="password_confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>

                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="<?= base_url('auth/login') ?>">
                                <span>Login</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
    <!--/ Content -->

    <!-- Include Scripts -->
    <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
    <!-- BEGIN: Vendor JS-->
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/jquery/jquery1e84.js?id=0f7eb1f3a93e3e19e8505fd8c175925a ') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/popper/popper0a73.js?id=baf82d96b7771efbcc05c3b77135d24c ') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/js/bootstrapcfc4.js?id=4648227467e3fd3f4cf976cfb0e43aea ') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar6188.js?id=44b8e955848dc0c56597c09f6aebf89a') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/hammer/hammer2de0.js?id=0a520e103384b609e3c9eb3b732d1be8') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/typeahead-js/typeahead60e7.js?id=f6bda588c16867a6cc4158cb4ed37ec6') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/js/menu2dc9.js?id=c6ce30ded4234d0c4ca0fb5f2a2990d8') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js') ?>"></script>
    <script src="<?= base_url('demo-1/demo/assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js') ?>"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="<?= base_url('demo-1/demo/assets/js/maind63d.js?id=6bea3f2e092d5fe7327c226f3242f31b') ?>"></script>

    <!-- END: Theme JS-->
    <!-- Pricing Modal JS-->
    <!-- END: Pricing Modal JS-->
    <!-- BEGIN: Page JS-->

    <!-- END: Page JS -->
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
</body>

</html>