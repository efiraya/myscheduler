<?= $this->extend('base_view') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: bold;">WELCOME TO MY ACTIVITY SCHEDULER !</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <h5 class="nk-block-title" style="font-weight: bold;">Today's Schedule</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered rounded-3">
                                <thead>
                                    <tr>
                                        <th>Activity Name</th>
                                        <th>Deadline</th>
                                        <th>Duration (hr)</th>
                                        <th>Type</th>
                                        <th>Urgency</th> <!-- Change to Urgency -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($noSchedule): ?>
                                        <td colspan="5">No schedule found.</td>
                                    <?php else: ?>
                                        <?php foreach ($schedules as $activity): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($activity['activity_name']) ?></td>
                                                <td><?= htmlspecialchars($activity['deadline']) ?></td>
                                                <td><?= htmlspecialchars($activity['duration']) ?></td>
                                                <td><?= htmlspecialchars($activity['activity_type']) ?></td>
                                                <td style="text-align-last: center;">
                                                    <?php if (htmlspecialchars($activity['urgency']) == 'critical') { ?>
                                                        <span class="badge badge-center bg-danger rounded-pill">!</span>
                                                    <?php } else if (htmlspecialchars($activity['urgency']) == 'high') { ?>
                                                        <span class="badge badge-center bg-warning rounded-pill">!</span>
                                                    <?php } else if (htmlspecialchars($activity['urgency']) == 'medium') { ?>
                                                        <span class="badge badge-center bg-primary rounded-pill" style="background-color    :#f0fb49 !important;">!</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-center bg-success rounded-pill">!</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <h5 class="nk-block-title" style="font-weight: bold;">Missed Deadline</h5>
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped table-hover table-bordered rounded-3">
                                <thead>
                                    <tr>
                                        <th>Activity Name</th>
                                        <th>Deadline</th>
                                        <th>Duration (hr)</th>
                                        <th>Type</th>
                                        <th>Urgency</th> <!-- Change to Urgency -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($oneWeekAgo)): ?>
                                        <td colspan="5">No schedule found.</td>
                                    <?php else: ?>
                                        <?php foreach ($oneWeekAgo as $valAcativity): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($valAcativity['activity_name']) ?></td>
                                                <td><?= htmlspecialchars($valAcativity['deadline']) ?></td>
                                                <td><?= htmlspecialchars($valAcativity['duration']) ?></td>
                                                <td><?= htmlspecialchars($valAcativity['activity_type']) ?></td>
                                                <td style="text-align-last: center;">
                                                    <?php if (htmlspecialchars($valAcativity['urgency']) == 'critical') { ?>
                                                        <span class="badge badge-center bg-danger rounded-pill">!</span>
                                                    <?php } else if (htmlspecialchars($valAcativity['urgency']) == 'high') { ?>
                                                        <span class="badge badge-center bg-warning rounded-pill">!</span>
                                                    <?php } else if (htmlspecialchars($valAcativity['urgency']) == 'medium') { ?>
                                                        <span class="badge badge-center bg-primary rounded-pill" style="background-color    :#f0fb49 !important;">!</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-center bg-success rounded-pill">!</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <p>First time user? <a href="/help">Click here</a> for more guidance on how to use the website!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>