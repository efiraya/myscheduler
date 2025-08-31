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
                        <h4 class="title nk-block-title">Edit Activity</h4>
                        <div class="preview-block">
                            <form class="input-form" action="/activity/update/<?= $activity['id'] ?>" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="activity_name">Activity Name:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="activity_name" class="form-control" value="<?= esc($activity['activity_name']) ?>" required id="activity_name">
                                        <small id="wordLimitNotification" style="color: red; display: none;">
                                            Maksimal 20 kata diperbolehkan.
                                        </small>
                                                <?= session()->getFlashdata('error_activity_name') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    $datetime = $activity['deadline'];

                                    // Pisahkan date dan time
                                    $date = date('Y-m-d', strtotime($datetime));
                                    $time = date('H:i:s', strtotime($datetime));
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php $isEditing = isset($activity) && !empty($activity); ?>
                                            <label class="form-label" for="deadline">Deadline Date:</label>
                                            <div class="form-control-wrap">
                                                <input type="date" name="deadline_date" class="form-control" <?= !$isEditing ? 'min="' . date('Y-m-d\TH:i') . '"' : '' ?> 
                                                value="<?= esc($date) ?>" required>
                                                <?= session()->getFlashdata('error_deadline') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php $isEditing = isset($activity) && !empty($activity); ?>
                                            <label class="form-label" for="deadline">Deadline Time:</label>
                                            <div class="form-control-wrap">
                                                <input type="time" name="deadline_time" class="form-control" <?= !$isEditing ? 'min="' . date('Y-m-d\TH:i') . '"' : '' ?> 
                                                value="<?= esc($time) ?>" required>
                                                <?= session()->getFlashdata('error_deadline') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="duration">Duration (in hours):</label>
                                            <div class="form-control-wrap">
                                                <input type="number" name="duration" class="form-control" min="1" value="<?= esc($activity['duration']) ?>" required>
                                                <?= session()->getFlashdata('error_duration') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="activity_type">Activity Type:</label>
                                            <div class="form-control-wrap">
                                                <select name="activity_type" class="form-control">
                                                    <option value="college" <?= $activity['activity_type'] == 'college' ? 'selected' : '' ?>>College</option>
                                                    <option value="work" <?= $activity['activity_type'] == 'work' ? 'selected' : '' ?>>Work</option>
                                                    <option value="organization" <?= $activity['activity_type'] == 'organization' ? 'selected' : '' ?>>Organization</option>
                                                    <option value="personal" <?= $activity['activity_type'] == 'personal' ? 'selected' : '' ?>>Personal</option>
                                                </select>
                                                <?= session()->getFlashdata('error_activity_type') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="description">Description:</label>
                                            <div class="form-control-wrap">
                                                <textarea name="description" class="form-control" style="white-space: pre-wrap;"><?= esc($activity['description']) ?></textarea>
                                                <?= session()->getFlashdata('error_description') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="description">Completion Status:</label>
                                            <div class="form-control-wrap">
                                                <select name="completion_stat" id="" class="form-control" required>
                                                    <option value="pending" @if($activity['completion_stat'] == 'pending')selected @endif>Pending</option>
                                                    <option value="completed" @if($activity['completion_stat'] == 'completed')selected @endif>Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fixed_deadline">Fixed Deadline:</label>
                                        <input type="radio" name="fixed_deadline" value="1" <?= old('fixed_deadline', $activity['fixed_deadline']) === '1' ? 'checked' : '' ?>> Yes
                                        <input type="radio" name="fixed_deadline" value="0" <?= old('fixed_deadline', $activity['fixed_deadline']) === '0' ? 'checked' : '' ?>> No
                                        <?= session()->getFlashdata('error_fixed_deadline') ?>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Update Activity</button>
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
    document.getElementById('activity_name').addEventListener('input', function () {
        const input = this.value;
        const words = input.trim().split(/\s+/); // Pisahkan input berdasarkan spasi
        const notification = document.getElementById('wordLimitNotification');
    
        if (words.length > 20) {
            // Tampilkan pesan jika kata lebih dari 20
            notification.style.display = 'inline';
            // Hapus kata-kata lebih dari batas
            this.value = words.slice(0, 20).join(' ');
        } else {
            // Sembunyikan pesan jika dalam batas
            notification.style.display = 'inline';
        }
    });
</script>
<?= $this->endSection() ?>