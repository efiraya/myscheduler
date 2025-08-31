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
                    <h4 class="nk-block-title" style="font-weight: bold;">Data User
                        <span><a href="/user/create" class="btn btn-primary" style="float: right;">Add User</a></span>
                    </h4>
                    <div class="card-inner">
                        <table class="table table-responsive" id="myTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data)): ?>
                                    <?php foreach ($data as $value): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($value['full_name']) ?></td>
                                            <td><?= htmlspecialchars($value['username']) ?></td>
                                            <td><?= htmlspecialchars($value['email']) ?></td>
                                            <td>
                                                <a href="/user/edit/<?= $value['id'] ?>" class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>
                                                <a href="#modalDelete_<?= $value['id'] ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal">Delete</a>
                                            </td>
                                            <!-- COMPLETION CHECKBOX -->

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No activities found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($activities)): ?>
    <?php foreach ($activities as $activites): ?>
        <div class="modal fade" tabindex="-1" id="modalDelete_<?= $activites['id'] ?>">
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
                        <p>You want to delete this data ?</p>
                        <div class="swal2-actions">
                            <a href="/user/delete/<?= $activites['id'] ?>" type="button" class="btn btn-success" aria-label="" style="display: inline-block; border-left-color: rgb(30, 224, 172) !important; border-right-color: rgb(30, 224, 172) !important;">Yes, delete it!</a>
                            <a href="#" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>