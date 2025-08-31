<?= $this->extend('base_view') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <h4 class="card-title" style="font-weight: bold;">Help</h4>
                        <p>Welcome to MyScheduler Help page! Here is a complete guide to help you utilize all the features available on this website.</p>
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
                        <h5 class="nk-block-title" style="font-weight: bold;">1. Dashboard</h5>
                        <p><b>Dashboard</b> page will provide you a summary of your daily activities. This page can be used to keep track of your daily priorities. Here are some of the features available on the dashboard page:</p>
                        <ul>
                            <li><b>Today's Activity:</b> Shows a list of recommended activities to complete today.</li>
                            <li><b>Missed Deadline:</b> Shows a list of activities that have passed their deadlines.</li>
                            <li>
                                <b>Urgency Column:</b> Indicates the urgency level of each activity to help you prioritize tasks more effectively. The urgency level is categorized into four levels:
                                <ul>
                                    <li><b>Low:</b> <span class="badge badge-center bg-success rounded-pill">!</span> - Indicates tasks that are not time-sensitive.</li>
                                    <li><b>Medium:</b> <span class="badge badge-center bg-primary rounded-pill" style="background-color    :#f0fb49 !important;">!</span> - Indicates tasks that require attention but are not immediately pressing.</li>
                                    <li><b>High:</b> <span class="badge badge-center bg-warning rounded-pill">!</span> - Indicates tasks that are important and should be prioritized soon.</li>
                                    <li><b>Critical:</b> <span class="badge badge-center bg-danger rounded-pill">!</span> - Indicates tasks that require immediate action and should be completed as soon as possible.</li>
                                </ul>
                                The <b>Critical</b> level highlights tasks that demand the utmost urgency and should not be postponed.
                            </li>
                        </ul>
                        <hr>
                        <h5 class="nk-block-title" style="font-weight: bold;">2. Adding New Activities</h5>
                        <p>To add a new activity:</p>
                        <ol>
                            <li>Go to the <b>Activity Input</b> page.</li>
                            <li>Fill in the activity form with the following details:</li>
                            <ul>
                                <li><b>Activity Name: </b>The name of your activity.</li>
                                <li><b>Deadline: </b>The date and time by which the activity should be completed.</li>
                                <li><b>Duration: </b>The estimated time that you expect to complete the activity.</li>
                                <li><b>Activity Type: </b>The category or nature of the activity.</li>
                                <li><b>Activity Description: </b>(Optional) Additional information or description about the activity.</li>
                                <li><b>Fixed or Flexible Deadline: </b>Specify whether the activity has a strict deadline that must be met (fixed) or if the deadline is flexible and can be adjusted as needed.</li>
                            </ul>
                            <li>Click the <b>Add Activity</b> button to add the activity to the system.</li>
                        </ol>
                        <hr>
                        <h5 class="nk-block-title" style="font-weight: bold;">3. Managing Activities</h5>
                        <p>The <b>Activity List</b> page allows you to view and manage your activities. Here’s what you can do:</p>
                        <ul>
                            <li><b>Edit Activity: </b>Click the <b>Edit</b> icon to update activity details.</li>
                            <li><b>Delete Activity: </b>Click the <b>Delete</b> icon to remove an activity from the list.</li>
                            <li><b>Create an Activity Schedule:</b></li>
                            <ol>
                                <li>Click the <b>Schedule Activity</b> button.</li>
                                <li>You will be redirected to the <b>Calculation Result</b> page, where the system-generated schedule will be displayed.</li>
                            </ol>
                            <li><b>Mark Activity as Completed: </b>Click the <b>Complete</b> button in the status column to mark the activity as finished. Completed activities will no longer appear in the schedule.</li>
                        </ul>
                        <hr>
                        <h5 class="nk-block-title" style="font-weight: bold;">4. Viewing Activity History</h5>
                        <p>The <b>Activity History</b> page displays completed activities and those that missed their deadlines:</p>
                        <ul>
                            <li><b>Completed Status: </b>Indicates activities that have been finished.</li>
                            <li><b>Pending Status: </b>Indicates activities that missed their deadlines and are yet to be completed.</li>
                        </ul>
                        <p>If you have completed an activity with a <b>Pending</b> status:</p>
                        <ol>
                            <li>Go to the <b>Activity List</b> page.</li>
                            <li>Click the <b>Complete</b> button to change its status to <b>Completed</b>.</li>
                        </ol>
                        <p><b>Note:</b> To revert the status to <b>Pending</b>, go to the <b>Activity List</b> page and click the <b>Edit</b> button. Then, update the activity status by selecting the desired <b>Completion Status</b> of the activity.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="nk-content-inner">
    <div class="nk-content-body mt-2">
        <div class="components-preview wide-md mx-auto">
            <div class="nk-block nk-block-lg">
                <div class="card card-bordered card-preview" style="padding:30px;">
                    <h4 class="nk-block-title" style="font-weight: bold;">Frequently Asked Questions (FAQ) </h4><br>
                    <div>
                        <div class="accordion" id="accordionExample">
                            <div class="card accordion-item active">
                                <h2 class="accordion-header" id="headingOne">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                    1. What should I do if I want to reschedule an activity?
                                    </button>
                                </h2>

                                <div id="accordionOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    You can return to the <b>Activity List</b> page, edit the relevant activity, and recreate the schedule using the <b>Schedule Activity</b> feature.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                    2. Can I view deleted activities?
                                    </button>
                                </h2>
                                <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    Unfortunately, deleted activities cannot be recovered. Please double-check before deleting an activity.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                    3. How does the system prioritize activities in the schedule?
                                    </button>
                                </h2>
                                <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    The system automatically recommends activities based on urgency and deadlines. You can adjust priorities by marking activities as completed or editing their details.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card-inner">
                        <ul>
                            <li>Go to "Add New Activity" to add your tasks or activities.</li>
                            <li>Check "View Activities" to see your saved tasks and edit or delete them if needed.</li>
                            <li>"Calculate Schedule" will generate an optimized schedule for your activities based on priority.</li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>