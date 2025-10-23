<?php
/**
 * Client Assessment Page (Placeholder)
 */

session_start();
require_once '../includes/Auth.php';
require_once '../includes/Database.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Check if client has completed assessments
$clientId = $user['client_id'];
$completedAssessments = [];
$hasCompletedAssessment = false;

try {
    $completedAssessments = $db->fetchAll(
        "SELECT a.*, DATE_FORMAT(a.completed_at, '%M %d, %Y at %h:%i %p') as formatted_date 
         FROM assessments a 
         WHERE a.client_id = :client_id 
         AND a.status = 'completed' 
         ORDER BY a.completed_at DESC",
        ['client_id' => $clientId]
    );
    $hasCompletedAssessment = count($completedAssessments) > 0;
} catch (Exception $e) {
    error_log("Error fetching assessments: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/assessment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="dashboard-nav">
        <div class="nav-brand">
            <i class="fas fa-hands-helping"></i>
            <span>KioskHelp</span>
        </div>
        <div class="nav-user">
            <span>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</span>
            <a href="logout.php" class="btn btn-secondary btn-small">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <h1>Comprehensive Needs Assessment</h1>
                <p>Help us understand your needs to connect you with the right services</p>
            </div>

            <?php if ($hasCompletedAssessment): ?>
            <div class="alert alert-success">
                <p><strong><i class="fas fa-check-circle"></i> Assessment Completed</strong></p>
                <p>You have completed <?php echo count($completedAssessments); ?> assessment(s). Your most recent assessment was completed on <?php echo htmlspecialchars($completedAssessments[0]['formatted_date']); ?>.</p>
                <p>Would you like to take another assessment to update your service needs?</p>
                <div style="margin-top: 1rem;">
                    <button onclick="showPreviousAssessments()" class="btn btn-secondary">
                        <i class="fas fa-history"></i> View Previous Assessments
                    </button>
                    <button onclick="startNewAssessment()" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Take New Assessment
                    </button>
                </div>
            </div>

            <!-- Previous Assessments Section (hidden by default) -->
            <div id="previousAssessments" class="card" style="display: none; margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-history"></i> Previous Assessments
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date Completed</th>
                                    <th>Priority Score</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($completedAssessments as $assessment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assessment['formatted_date']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $assessment['priority_score'] > 15 ? 'error' : ($assessment['priority_score'] > 8 ? 'warning' : 'success'); ?>">
                                            <?php echo $assessment['priority_score']; ?> points
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Completed
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_assessment.php?id=<?php echo $assessment['id']; ?>" class="btn btn-small btn-secondary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- New Assessment Form (hidden by default when assessments exist) -->
            <div id="newAssessmentSection" style="display: none;">
            <?php endif; ?>
            
            <div class="alert alert-info">
                <p><strong>Important Information:</strong></p>
                <p>This comprehensive smart intake assessment will evaluate your needs across multiple areas including housing, food security, healthcare, mental health, employment, legal assistance, transportation, and family services. Based on your responses, we'll assign you tasks and appointments with various service providers in your region based on what you need in life.</p>
                <p><strong>Note:</strong> All questions are optional and not mandatory. If you don't feel comfortable with a question, simply skip over it.</p>
            </div>

            <!-- Progress Bar -->
            <div class="assessment-progress">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-text">
                    <span id="currentStep">1</span> of <span id="totalSteps">8</span> domains completed
                </div>
            </div>

            <form id="assessmentForm" class="assessment-form">
                <!-- Housing Stability -->
                <div class="assessment-section active" data-domain="housing">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-home"></i> Housing Stability
                            <span class="domain-number">Domain 1 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your current living situation and housing needs.</p>
                            
                            <div class="form-group">
                                <label>What is your current living situation?</label>
                                <select name="housing_situation" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="stable_housing">Stable housing (rent/own)</option>
                                    <option value="temporary_housing">Temporary housing (staying with friends/family)</option>
                                    <option value="shelter">Emergency shelter</option>
                                    <option value="transitional">Transitional housing</option>
                                    <option value="homeless">Homeless/unsheltered</option>
                                    <option value="at_risk">At risk of losing housing</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>How satisfied are you with your current housing?</label>
                                <select name="housing_satisfaction" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="very_satisfied">Very satisfied</option>
                                    <option value="satisfied">Satisfied</option>
                                    <option value="neutral">Neutral</option>
                                    <option value="dissatisfied">Dissatisfied</option>
                                    <option value="very_dissatisfied">Very dissatisfied</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Do you need assistance with housing?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="housing_assistance" value="yes">
                                        <span>Yes, I need housing assistance</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="housing_assistance" value="no">
                                        <span>No, I'm stable</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="housing_assistance" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional housing concerns (optional):</label>
                                <textarea name="housing_notes" class="form-control" rows="3" placeholder="Share any additional housing concerns or needs..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Food Security -->
                <div class="assessment-section" data-domain="food">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-utensils"></i> Food Security
                            <span class="domain-number">Domain 2 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your access to nutritious food.</p>
                            
                            <div class="form-group">
                                <label>How often do you have access to enough food?</label>
                                <select name="food_access" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="always">Always</option>
                                    <option value="usually">Usually</option>
                                    <option value="sometimes">Sometimes</option>
                                    <option value="rarely">Rarely</option>
                                    <option value="never">Never</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>In the past month, have you skipped meals due to lack of money or resources?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="food_skip_meals" value="yes_often">
                                        <span>Yes, often</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="food_skip_meals" value="yes_sometimes">
                                        <span>Yes, sometimes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="food_skip_meals" value="no">
                                        <span>No</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="food_skip_meals" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Do you need assistance with food?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="food_assistance" value="yes">
                                        <span>Yes, I need food assistance</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="food_assistance" value="no">
                                        <span>No, I have enough food</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="food_assistance" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional food concerns (optional):</label>
                                <textarea name="food_notes" class="form-control" rows="3" placeholder="Share any additional food security concerns..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Healthcare Access -->
                <div class="assessment-section" data-domain="healthcare">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-heartbeat"></i> Healthcare Access
                            <span class="domain-number">Domain 3 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your access to medical, dental, and vision care.</p>
                            
                            <div class="form-group">
                                <label>Do you currently have health insurance?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="healthcare_insurance" value="yes">
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="healthcare_insurance" value="no">
                                        <span>No</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="healthcare_insurance" value="unsure">
                                        <span>Not sure</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="healthcare_insurance" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>When was your last medical checkup?</label>
                                <select name="healthcare_last_checkup" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="within_6months">Within the last 6 months</option>
                                    <option value="within_year">Within the last year</option>
                                    <option value="1_2years">1-2 years ago</option>
                                    <option value="over_2years">Over 2 years ago</option>
                                    <option value="never">Never had one</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>What healthcare services do you need? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="primary_care">
                                        <span>Primary care</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="dental">
                                        <span>Dental care</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="vision">
                                        <span>Vision care</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="prescription">
                                        <span>Prescription assistance</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="specialist">
                                        <span>Specialist care</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="healthcare_needs[]" value="none">
                                        <span>None at this time</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional healthcare concerns (optional):</label>
                                <textarea name="healthcare_notes" class="form-control" rows="3" placeholder="Share any additional healthcare needs or concerns..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mental Health -->
                <div class="assessment-section" data-domain="mental_health">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-brain"></i> Mental Health
                            <span class="domain-number">Domain 4 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your emotional wellbeing and support needs.</p>
                            
                            <div class="form-group">
                                <label>How would you rate your current mental health?</label>
                                <select name="mental_health_rating" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="excellent">Excellent</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                    <option value="very_poor">Very poor</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Are you currently experiencing any of the following? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="anxiety">
                                        <span>Anxiety</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="depression">
                                        <span>Depression</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="stress">
                                        <span>High stress</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="trauma">
                                        <span>Trauma/PTSD</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="substance">
                                        <span>Substance use concerns</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="mental_health_symptoms[]" value="none">
                                        <span>None of the above</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Would you like support for mental health or emotional wellbeing?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="mental_health_support" value="yes">
                                        <span>Yes, I'd like support</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="mental_health_support" value="maybe">
                                        <span>Maybe, want to learn more</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="mental_health_support" value="no">
                                        <span>No, not at this time</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="mental_health_support" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-warning" style="margin-top: 1rem;">
                                <p><strong>Crisis Resources:</strong></p>
                                <p>If you're in crisis: Call 988 (Suicide & Crisis Lifeline) or Text HOME to 741741 (Crisis Text Line)</p>
                            </div>

                            <div class="form-group">
                                <label>Additional mental health concerns (optional):</label>
                                <textarea name="mental_health_notes" class="form-control" rows="3" placeholder="Share any additional mental health concerns..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment -->
                <div class="assessment-section" data-domain="employment">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-briefcase"></i> Employment
                            <span class="domain-number">Domain 5 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your employment status and job training needs.</p>
                            
                            <div class="form-group">
                                <label>What is your current employment status?</label>
                                <select name="employment_status" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="employed_full">Employed full-time</option>
                                    <option value="employed_part">Employed part-time</option>
                                    <option value="self_employed">Self-employed</option>
                                    <option value="unemployed_seeking">Unemployed, seeking work</option>
                                    <option value="unemployed_not_seeking">Unemployed, not seeking work</option>
                                    <option value="student">Student</option>
                                    <option value="retired">Retired</option>
                                    <option value="disabled">Unable to work (disability)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Are you interested in job training or education programs?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="employment_training" value="yes">
                                        <span>Yes, very interested</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="employment_training" value="maybe">
                                        <span>Maybe, want to learn more</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="employment_training" value="no">
                                        <span>No, not at this time</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="employment_training" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>What employment services would be helpful? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="job_search">
                                        <span>Job search assistance</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="resume">
                                        <span>Resume/interview help</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="skills_training">
                                        <span>Skills training</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="education">
                                        <span>Education programs</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="work_clothes">
                                        <span>Work clothing/supplies</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="employment_services[]" value="none">
                                        <span>None at this time</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional employment concerns (optional):</label>
                                <textarea name="employment_notes" class="form-control" rows="3" placeholder="Share any additional employment needs or goals..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal Assistance -->
                <div class="assessment-section" data-domain="legal">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-gavel"></i> Legal Assistance
                            <span class="domain-number">Domain 6 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about any legal issues or needs you may have.</p>
                            
                            <div class="form-group">
                                <label>Do you currently have any legal issues or concerns?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="legal_issues" value="yes">
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="legal_issues" value="no">
                                        <span>No</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="legal_issues" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>What type of legal assistance do you need? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="family_law">
                                        <span>Family law</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="housing">
                                        <span>Housing/eviction</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="employment">
                                        <span>Employment issues</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="immigration">
                                        <span>Immigration</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="criminal">
                                        <span>Criminal record/expungement</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="benefits">
                                        <span>Public benefits</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="legal_services[]" value="none">
                                        <span>None at this time</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional legal concerns (optional):</label>
                                <textarea name="legal_notes" class="form-control" rows="3" placeholder="Share any additional legal concerns..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transportation -->
                <div class="assessment-section" data-domain="transportation">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bus"></i> Transportation
                            <span class="domain-number">Domain 7 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your transportation and mobility needs.</p>
                            
                            <div class="form-group">
                                <label>How do you usually get around?</label>
                                <select name="transportation_method" class="form-control">
                                    <option value="">Select an option (optional)</option>
                                    <option value="own_car">Own car</option>
                                    <option value="public_transit">Public transportation</option>
                                    <option value="walk_bike">Walk/bicycle</option>
                                    <option value="rides">Rides from friends/family</option>
                                    <option value="rideshare">Rideshare/taxi</option>
                                    <option value="limited">Limited or no transportation</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Does lack of transportation prevent you from accessing services?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="transportation_barrier" value="yes_often">
                                        <span>Yes, often</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="transportation_barrier" value="yes_sometimes">
                                        <span>Yes, sometimes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="transportation_barrier" value="no">
                                        <span>No</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="transportation_barrier" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>What transportation assistance would be helpful? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="transportation_needs[]" value="bus_passes">
                                        <span>Bus passes/tokens</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="transportation_needs[]" value="gas_vouchers">
                                        <span>Gas vouchers</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="transportation_needs[]" value="ride_program">
                                        <span>Ride assistance program</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="transportation_needs[]" value="bike">
                                        <span>Bicycle</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="transportation_needs[]" value="none">
                                        <span>None at this time</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional transportation concerns (optional):</label>
                                <textarea name="transportation_notes" class="form-control" rows="3" placeholder="Share any additional transportation needs..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Services -->
                <div class="assessment-section" data-domain="family">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-users"></i> Family Services
                            <span class="domain-number">Domain 8 of 8</span>
                        </div>
                        <div class="card-body">
                            <p class="section-description">Tell us about your family situation and childcare needs.</p>
                            
                            <div class="form-group">
                                <label>Do you have children under 18?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="family_children" value="yes">
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="family_children" value="no">
                                        <span>No</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="family_children" value="skip">
                                        <span>Prefer not to answer</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>What family services would be helpful? (Check all that apply)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="childcare">
                                        <span>Childcare assistance</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="parenting">
                                        <span>Parenting classes/support</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="youth_programs">
                                        <span>Youth programs</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="family_counseling">
                                        <span>Family counseling</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="domestic_violence">
                                        <span>Domestic violence support</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="family_services[]" value="none">
                                        <span>None at this time</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional family service needs (optional):</label>
                                <textarea name="family_notes" class="form-control" rows="3" placeholder="Share any additional family service needs..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="assessment-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeSection(-1)">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeSection(1)">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i class="fas fa-check"></i> Complete Assessment
                    </button>
                </div>
            </form>
            
            <?php if ($hasCompletedAssessment): ?>
            </div><!-- End new assessment section -->
            <?php endif; ?>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function showPreviousAssessments() {
            document.getElementById('previousAssessments').style.display = 'block';
        }
        
        function startNewAssessment() {
            document.getElementById('newAssessmentSection').style.display = 'block';
            // Scroll to the assessment form
            document.getElementById('newAssessmentSection').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Assessment form navigation
        let currentSection = 0;
        const sections = document.querySelectorAll('.assessment-section');
        const totalSections = sections.length;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            showSection(currentSection);
            updateProgress();
        });

        function showSection(n) {
            sections.forEach((section, index) => {
                section.classList.remove('active');
                if (index === n) {
                    section.classList.add('active');
                }
            });

            // Update buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            if (n === 0) {
                prevBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'inline-block';
            }

            if (n === totalSections - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function changeSection(direction) {
            currentSection += direction;
            if (currentSection >= totalSections) {
                currentSection = totalSections - 1;
            }
            if (currentSection < 0) {
                currentSection = 0;
            }
            showSection(currentSection);
            updateProgress();
        }

        function updateProgress() {
            const progress = ((currentSection + 1) / totalSections) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
            document.getElementById('currentStep').textContent = currentSection + 1;
            document.getElementById('totalSteps').textContent = totalSections;
        }

        // Form submission
        document.getElementById('assessmentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            try {
                const formData = new FormData(this);
                
                // Collect all form data including checkboxes
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key.endsWith('[]')) {
                        const arrayKey = key.slice(0, -2);
                        if (!data[arrayKey]) {
                            data[arrayKey] = [];
                        }
                        data[arrayKey].push(value);
                    } else {
                        data[key] = value;
                    }
                }

                const response = await fetch('../api/submit_assessment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', 'Assessment completed successfully! Redirecting to dashboard...');
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 2000);
                } else {
                    showAlert('error', result.message || 'Failed to submit assessment. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Complete Assessment';
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Complete Assessment';
            }
        });
    </script>
</body>
</html>
