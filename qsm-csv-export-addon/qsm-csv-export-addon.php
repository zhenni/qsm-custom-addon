<?php
/*
Plugin Name: QSM CSV Export Addon
Description: An addon for Quiz and Survey Master that exports quiz results to CSV files.
Version: 1.1
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path
define('QSM_CSV_EXPORT_ADDON_PATH', plugin_dir_path(__FILE__));

// Include the export functions file
include_once(QSM_CSV_EXPORT_ADDON_PATH . 'includes/export-functions.php');

add_action('admin_notices', 'show_current_screen_id');

function show_current_screen_id() {
    $screen = get_current_screen();
    echo '<div class="notice notice-info"><p>Current Screen ID: ' . esc_html($screen->id) . '</p></div>';
}

// Add the export button to the QSM results page
add_action('admin_notices', 'qsm_csv_export_button');

function qsm_csv_export_button() {
    $screen = get_current_screen();
    if ($screen->id == 'qsm_page_mlw_quiz_results') {
        echo '<div class="notice notice-info"><p><a href="' . esc_url(admin_url('admin-post.php?action=export_qsm_csv')) . '" class="button button-primary">Export to CSV</a></p></div>';
    }
}

// // Handle the CSV export action
// add_action('admin_post_export_qsm_csv', 'qsm_export_results_to_csv');

// function qsm_export_results_to_csv() {
//     if (!current_user_can('manage_options')) {
//         wp_die('Unauthorized user');
//     }

//     // Get quiz results
//     $results = qsm_get_all_results();

//     // Set CSV headers
//     header('Content-Type: text/csv');
//     header('Content-Disposition: attachment;filename=quiz-results.csv');

//     // Open output stream
//     $output = fopen('php://output', 'w');

//     // Output header row (customize based on your data structure)
//     fputcsv($output, ['Quiz ID', 'User', 'Results', 'Date']);

//     // Output data rows
//     foreach ($results as $result) {
//         fputcsv($output, [
//             $result['quiz_id'],
//             $result['user_name'],
//             $result['results'],
//             $result['date']
//         ]);
//     }

//     fclose($output);
//     exit;
// }

// function qsm_get_all_results() {
//     global $wpdb;

//     // Adjust the table name if your database prefix is different
//     $table_name = $wpdb->prefix . 'mlw_results';

//     // Debug: Output the table name to the log
//     error_log('Table Name: ' . $table_name);

//     // Query to get quiz results from the QSM results table
//     $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
//     // Debug: Log the query results
//     error_log('Query Results: ' . print_r($results, true));

//     // Check if results are retrieved correctly
//     if (empty($results)) {
//         return [];
//     }

//     // Process results if necessary
//     $processed_results = [];
//     foreach ($results as $result) {
//         $processed_results[] = [
//             'quiz_id' => $result['quiz_name'],
//             'user_name' => $result['name'], // Adjust based on actual column
//             'results' => $result['quiz_results'], // Adjust based on actual column
//             'date' => $result['time_taken_real'],
//         ];
//     }

//     return $processed_results;
// }



// // SELECT r.quiz_id, r.name, r.correct_score, r.time_taken_real, q.question_name, q.answer_array
// // FROM wp_mlw_results r
// // RIGHT JOIN wp_mlw_questions q ON r.quiz_id = q.quiz_id
// // LIMIT 10;

// function qsm_get_all_results() {
//     global $wpdb;

//     // Adjust the table names if your database prefix is different
//     $results_table = $wpdb->prefix . 'mlw_results';
//     $questions_table = $wpdb->prefix . 'mlw_questions';

//     // Query to get quiz results along with user responses
//     $query = "
//         SELECT 
//             r.quiz_id, 
//             r.name AS user_name, 
//             r.time_taken_real,
//             q.question_name, 
//             q.question_type, 
//             q.answer_array AS user_answer, 
//             q.correct_answer AS correct_answer 
//         FROM $results_table r
//         LEFT JOIN $questions_table q 
//         ON r.quiz_id = q.quiz_id
//         ORDER BY r.quiz_id, r.time_taken_real
//     ";

//     $results = $wpdb->get_results($query, ARRAY_A);

//     if (empty($results)) {
//         return [];
//     }

//     return $results;
// }



function process_answer_data($data) {
    // Unserialize or JSON decode the data
    $processed_data = maybe_unserialize($data);
    if (is_string($processed_data)) {
        $processed_data = json_decode($processed_data, true);
    }

    // JSON encode the array to avoid array to string conversion warning
    return is_array($processed_data) ? json_encode($processed_data) : $processed_data;
}

function qsm_get_all_results() {
    global $wpdb;

    // Define table names
    $results_table = $wpdb->prefix . 'mlw_results';
    $questions_table = $wpdb->prefix . 'mlw_questions';

    // Create the SQL query
    $query = "
        SELECT 
            r.quiz_id, 
            r.name AS user_name, 
            r.time_taken_real,
            r.quiz_results AS user_answer,
            q.question_settings, 
            q.question_type, 
            q.answer_array AS question_answer
        FROM $results_table r
        LEFT JOIN $questions_table q 
        ON r.quiz_id = q.quiz_id
        ORDER BY r.quiz_id, r.time_taken_real
    ";

    // Execute the query
    $results = $wpdb->get_results($query, ARRAY_A);

    // Check if results are empty
    if (empty($results)) {
        return [];
    }

    // Process results, unserializing or decoding JSON where necessary
    $processed_results = [];
    foreach ($results as $result) {
        // Unserialize or JSON decode the question settings and answers

        // Call the function for each of the required fields
        $question_summary = process_answer_data($result['question_settings']);
        $question_answer_summary = process_answer_data($result['question_answer']);
        $user_answer_summary = process_answer_data($result['user_answer']);

        $processed_results[] = [
            'quiz_id' => $result['quiz_id'],
            'user_name' => $result['user_name'],
            'time_taken_real' => $result['time_taken_real'],
            'question_settings' => $question_summary,
            'question_type' => $result['question_type'],
            'question_answer' => $question_answer_summary,
            'user_answer' => $user_answer_summary,
        ];
    }

    return $processed_results;
}



add_action('admin_post_export_qsm_csv', 'qsm_export_results_to_csv');

function qsm_export_results_to_csv() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }

    // Get quiz results
    $results = qsm_get_all_results();

    // Set CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=quiz-results.csv');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Output header row
    fputcsv($output, ['Quiz ID', 'User Name', 'Time Taken', 'Question Settings', 'Question Type', 'Question Answer', 'User Answer']);

    // Output data rows
    foreach ($results as $result) {
        fputcsv($output, [
            $result['quiz_id'],
            $result['user_name'],
            $result['time_taken_real'],
            $result['question_settings'],
            $result['question_type'],
            $result['question_answer'],
            $result['user_answer'],
        ]);
    }

    fclose($output);
    exit;
}




