<?= $this->extend('base_view') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <?= $this->include('layouts/partials/notification') ?>
                        <h4 class="card-title" style="font-weight: bold;">Calculation Result: Activity Prioritization</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover rounded-3">
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
                                                <!--<td><?= htmlspecialchars($activity['final_weight']) ?></td>-->
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
                        <br><br>
                        <div class="table-responsive">
                            <hr>
                            <ul>
                                <li><b>Low:</b> <span class="badge badge-center bg-success rounded-pill">!</span> - Indicates tasks that are not time-sensitive.</li>
                                <li><b>Medium:</b> <span class="badge badge-center bg-primary rounded-pill" style="background-color    :#f0fb49 !important;">!</span> - Indicates tasks that require attention but are not immediately pressing.</li>
                                <li><b>High:</b> <span class="badge badge-center bg-warning rounded-pill">!</span> - Indicates tasks that are important and should be prioritized soon.</li>
                                <li><b>Critical:</b> <span class="badge badge-center bg-danger rounded-pill">!</span> - Indicates tasks that require immediate action and should be completed as soon as possible.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>