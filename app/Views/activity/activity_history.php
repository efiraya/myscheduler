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
                    <h4 class="nk-block-title" style="font-weight: bold;">Activity History</h4>
                    <p>This page will show the history of past activities that has either been completed or missed the deadline.</p>
                    <div class="card-inner">
                        <table class="table table-responsive" id="myTable">
                            <thead>
                                <tr>
                                    <th>Activity Name</th>
                                    <th>Description</th>
                                    <th>Deadline</th>
                                    <th>Completion Status</th>
                                </tr>
                            </thead>
                            <?php foreach ($activities as $activity): ?>
                                <tr>
                                    <td><?= esc($activity['activity_name']) ?></td>
                                    <td><?= esc($activity['description']) ?></td>
                                    <td><?= esc($activity['deadline']) ?></td>
                                    <td>
                                        <?php if($activity['completion_stat'] == 'pending'){ ?>
                                            Pending
                                        <?php }else{ ?>
                                            <?= $activity['completion_stat'] ? 'Completed' : 'Overdue' ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>