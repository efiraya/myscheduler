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
                        <h4 class="title nk-block-title">Input New Activity</h4>
                        <div class="preview-block">
                            <form action="/activity/submit" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Activity Name:</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="activity_name" class="form-control" required value="<?= old('activity_name') ?>" id="activity_name">
                                        <small id="wordLimitNotification" style="color: red; display: none;">
                                            Maksimal 20 kata diperbolehkan.
                                        </small>
                                                <?= session()->getFlashdata('error_activity_name') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Deadline Date:</label>
                                            <div class="form-control-wrap">
                                                <input type="date" name="deadline_date" class="form-control" min="<?= date('Y-m-d') ?>" required value="<?= old('deadline') ?>">
                                                <?= session()->getFlashdata('error_deadline') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Deadline Time:</label>
                                            <div class="form-control-wrap">
                                                <input type="time" name="deadline_time" class="form-control"  required value="<?= old('deadline') ?>">
                                                <?= session()->getFlashdata('error_deadline') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Duration (in hours):</label>
                                            <div class="form-control-wrap">
                                                <input type="number" name="duration" class="form-control" min="1" required value="<?= old('duration') ?>">
                                                <?= session()->getFlashdata('error_duration') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Activity Type:</label>
                                            <div class="form-control-wrap">
                                                <select name="activity_type" class="form-control" required>
                                                    <option value="" disabled selected>Select activity type</option>
                                                    <option value="college" <?= old('activity_type') === 'college' ? 'selected' : '' ?>>College</option>
                                                    <option value="work" <?= old('activity_type') === 'work' ? 'selected' : '' ?>>Work</option>
                                                    <option value="organization" <?= old('activity_type') === 'organization' ? 'selected' : '' ?>>Organization</option>
                                                    <option value="personal" <?= old('activity_type') === 'personal' ? 'selected' : '' ?>>Personal</option>
                                                </select>
                                                <?= session()->getFlashdata('error_activity_type') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Description:</label>
                                            <div class="form-control-wrap">
                                                <textarea name="description" class="form-control" style="white-space: pre-wrap;"><?= old('description') ?></textarea>
                                                <?= session()->getFlashdata('error_description') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fixed_deadline">Fixed Deadline:</label>
                                        <input type="radio" name="fixed_deadline" value="1" <?= old('fixed_deadline') === '1' ? 'checked' : '' ?>> Yes
                                        <input type="radio" name="fixed_deadline" value="0" <?= old('fixed_deadline') === '0' ? 'checked' : '' ?>> No
                                        <?= session()->getFlashdata('error_fixed_deadline') ?>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Add Activity</button>
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
<!-- <h2>Add New Activity</h2>
<form action="/activity/submit" method="post">
    <label for="activity_name">Activity Name:</label>
    <input type="text" name="activity_name" required>

    <label for="deadline">Deadline:</label>
    <input type="datetime-local" name="deadline" required>

    <label for="duration">Duration (in hours):</label>
    <input type="number" name="duration" min="1" required>

    <label for="activity_type">Activity Level:</label>
    <select name="activity_type" required>
        <option value="college">College</option>
        <option value="work">Work</option>
        <option value="organization">Organization</option>
        <option value="personal">Personal</option>
    </select>

    <label for="description">Description:</label>
    <textarea name="description"></textarea>

    <label for="fixed_deadline">Fixed Deadline:</label>
    <input type="radio" name="fixed_deadline" value="1" <?= old('fixed_deadline') === '1' ? 'checked' : '' ?>> Yes
    <input type="radio" name="fixed_deadline" value="0" <?= old('fixed_deadline') === '0' ? 'checked' : '' ?>> No

    <button type="submit">Add Activity</button>
</form> -->
<?= $this->endSection() ?>