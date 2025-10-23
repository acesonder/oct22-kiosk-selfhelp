<?php
/**
 * API Endpoint: Submit Assessment
 * Handles the submission of client assessment data
 */

session_start();
require_once '../includes/Database.php';
require_once '../includes/Auth.php';

header('Content-Type: application/json');

// Check authentication
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user = $auth->getCurrentUser();

try {
    $db = Database::getInstance();
    
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid data format');
    }
    
    // Start transaction
    $db->beginTransaction();
    
    // Get client_id
    $clientQuery = "SELECT id FROM clients WHERE user_id = :user_id";
    $clientResult = $db->fetch($clientQuery, ['user_id' => $user['id']]);
    
    if (!$clientResult) {
        throw new Exception('Client record not found');
    }
    
    $clientId = $clientResult['id'];
    
    // Create assessment record
    $assessmentQuery = "INSERT INTO assessments (client_id, assessment_type, status, completed_at) 
                       VALUES (:client_id, 'intake', 'completed', NOW())";
    $assessmentId = $db->insert($assessmentQuery, [
        'client_id' => $clientId
    ]);
    
    if (!$assessmentId) {
        throw new Exception('Failed to create assessment');
    }
    
    // Define domain mappings
    $domainMap = [
        'housing' => ['housing_situation', 'housing_satisfaction', 'housing_assistance', 'housing_notes'],
        'food' => ['food_access', 'food_skip_meals', 'food_assistance', 'food_notes'],
        'healthcare' => ['healthcare_insurance', 'healthcare_last_checkup', 'healthcare_needs', 'healthcare_notes'],
        'mental_health' => ['mental_health_rating', 'mental_health_symptoms', 'mental_health_support', 'mental_health_notes'],
        'employment' => ['employment_status', 'employment_training', 'employment_services', 'employment_notes'],
        'legal' => ['legal_issues', 'legal_services', 'legal_notes'],
        'transportation' => ['transportation_method', 'transportation_barrier', 'transportation_needs', 'transportation_notes'],
        'family' => ['family_children', 'family_services', 'family_notes']
    ];
    
    // Calculate priority score based on responses
    $priorityScore = 0;
    
    // Save assessment responses
    foreach ($domainMap as $domain => $fields) {
        foreach ($fields as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && $data[$field] !== 'skip') {
                $value = $data[$field];
                
                // Convert arrays to JSON
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                
                // Calculate priority level for this response
                $priority = calculatePriority($field, $data[$field]);
                $priorityScore += $priority;
                
                $responseQuery = "INSERT INTO assessment_responses 
                                 (assessment_id, domain, question_key, response_value, priority_level) 
                                 VALUES (:assessment_id, :domain, :question_key, :response_value, :priority_level)";
                
                $db->insert($responseQuery, [
                    'assessment_id' => $assessmentId,
                    'domain' => $domain,
                    'question_key' => $field,
                    'response_value' => $value,
                    'priority_level' => $priority
                ]);
            }
        }
    }
    
    // Update assessment with priority score
    $updateQuery = "UPDATE assessments SET priority_score = :score WHERE id = :id";
    $db->update($updateQuery, [
        'score' => $priorityScore,
        'id' => $assessmentId
    ]);
    
    // Auto-create referrals based on high-priority needs
    createAutoReferrals($db, $clientId, $assessmentId, $data, $priorityScore);
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Assessment completed successfully',
        'assessment_id' => $assessmentId,
        'priority_score' => $priorityScore
    ]);
    
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollback();
    }
    
    error_log("Assessment submission error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit assessment: ' . $e->getMessage()
    ]);
}

/**
 * Calculate priority level based on response
 */
function calculatePriority($field, $value) {
    // High priority indicators
    $highPriorityMap = [
        'housing_situation' => ['homeless', 'shelter', 'at_risk'],
        'housing_assistance' => ['yes'],
        'food_access' => ['rarely', 'never'],
        'food_skip_meals' => ['yes_often'],
        'food_assistance' => ['yes'],
        'healthcare_insurance' => ['no'],
        'mental_health_rating' => ['poor', 'very_poor'],
        'mental_health_support' => ['yes'],
        'employment_status' => ['unemployed_seeking'],
        'legal_issues' => ['yes'],
        'transportation_barrier' => ['yes_often'],
    ];
    
    // Check for high priority
    if (isset($highPriorityMap[$field])) {
        if (is_array($value)) {
            foreach ($value as $v) {
                if (in_array($v, $highPriorityMap[$field])) {
                    return 3; // High priority
                }
            }
        } else if (in_array($value, $highPriorityMap[$field])) {
            return 3; // High priority
        }
    }
    
    // Medium priority for assistance requests
    if (strpos($field, '_assistance') !== false && $value === 'yes') {
        return 2;
    }
    
    // Default low priority
    return 1;
}

/**
 * Create automatic referrals based on assessment
 */
function createAutoReferrals($db, $clientId, $assessmentId, $data, $priorityScore) {
    $referrals = [];
    
    // Housing referral
    if (isset($data['housing_assistance']) && $data['housing_assistance'] === 'yes') {
        $referrals[] = ['type' => 'housing', 'priority' => 'high'];
    }
    
    // Food referral
    if (isset($data['food_assistance']) && $data['food_assistance'] === 'yes') {
        $referrals[] = ['type' => 'food', 'priority' => 'high'];
    }
    
    // Healthcare referral
    if (isset($data['healthcare_insurance']) && $data['healthcare_insurance'] === 'no') {
        $referrals[] = ['type' => 'healthcare', 'priority' => 'medium'];
    }
    
    // Mental health referral
    if (isset($data['mental_health_support']) && $data['mental_health_support'] === 'yes') {
        $referrals[] = ['type' => 'mental_health', 'priority' => 'high'];
    }
    
    // Employment referral
    if (isset($data['employment_training']) && $data['employment_training'] === 'yes') {
        $referrals[] = ['type' => 'employment', 'priority' => 'medium'];
    }
    
    // Legal referral
    if (isset($data['legal_issues']) && $data['legal_issues'] === 'yes') {
        $referrals[] = ['type' => 'legal', 'priority' => 'medium'];
    }
    
    // Create referrals
    foreach ($referrals as $referral) {
        // Find a provider for this type
        $providerQuery = "SELECT id FROM service_providers 
                         WHERE provider_type = :type AND is_active = 1 
                         ORDER BY current_load ASC LIMIT 1";
        $provider = $db->fetch($providerQuery, ['type' => $referral['type']]);
        
        if ($provider) {
            $referralQuery = "INSERT INTO referrals 
                             (client_id, provider_id, assessment_id, referral_type, priority, status, notes) 
                             VALUES (:client_id, :provider_id, :assessment_id, :referral_type, :priority, 'pending', :notes)";
            
            $db->insert($referralQuery, [
                'client_id' => $clientId,
                'provider_id' => $provider['id'],
                'assessment_id' => $assessmentId,
                'referral_type' => $referral['type'],
                'priority' => $referral['priority'],
                'notes' => 'Auto-generated from intake assessment'
            ]);
            
            // Update provider load
            $db->update("UPDATE service_providers SET current_load = current_load + 1 WHERE id = :id", 
                       ['id' => $provider['id']]);
        }
    }
}
