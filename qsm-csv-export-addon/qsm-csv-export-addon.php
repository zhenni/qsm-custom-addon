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



// function process_answer_data($data) {
//     // Unserialize or JSON decode the data
//     $processed_data = maybe_unserialize($data);
//     if (is_string($processed_data)) {
//         $processed_data = json_decode($processed_data, true);
//     }

//     // JSON encode the array to avoid array to string conversion warning
//     return is_array($processed_data) ? json_encode($processed_data) : $processed_data;
// }

// function qsm_get_all_results_1() {
//     global $wpdb;

//     // Define table names
//     $results_table = $wpdb->prefix . 'mlw_results';
//     $questions_table = $wpdb->prefix . 'mlw_questions';

//     // Create the SQL query
//     $query = "
//         SELECT 
//             r.quiz_id, 
//             r.name AS user_name, 
//             r.time_taken_real,
//             r.quiz_results AS user_answer,
//             q.question_settings, 
//             q.question_type, 
//             q.answer_array AS question_answer
//         FROM $results_table r
//         LEFT JOIN $questions_table q 
//         ON r.quiz_id = q.quiz_id
//         ORDER BY r.quiz_id, r.time_taken_real
//     ";

//     // Execute the query
//     $results = $wpdb->get_results($query, ARRAY_A);

//     // Check if results are empty
//     if (empty($results)) {
//         return [];
//     }

//     // Process results, unserializing or decoding JSON where necessary
//     $processed_results = [];
//     foreach ($results as $result) {
//         // Unserialize or JSON decode the question settings and answers

//         // Call the function for each of the required fields
//         $question_summary = process_answer_data($result['question_settings']);
//         $question_answer_summary = process_answer_data($result['question_answer']);
//         $user_answer_summary = process_answer_data($result['user_answer']);

//         $processed_results[] = [
//             'quiz_id' => $result['quiz_id'],
//             'user_name' => $result['user_name'],
//             'time_taken_real' => $result['time_taken_real'],
//             'question_settings' => $question_summary,
//             'question_type' => $result['question_type'],
//             'question_answer' => $question_answer_summary,
//             'user_answer' => $user_answer_summary,
//         ];
//     }

//     return $processed_results;
// }


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

//     // Output header row
//     fputcsv($output, ['Quiz ID', 'User Name', 'Time Taken', 'Question Settings', 'Question Type', 'Question Answer', 'User Answer']);

//     // Output data rows
//     foreach ($results as $result) {
//         fputcsv($output, [
//             $result['quiz_id'],
//             $result['user_name'],
//             $result['time_taken_real'],
//             $result['question_settings'],
//             $result['question_type'],
//             $result['question_answer'],
//             $result['user_answer'],
//         ]);
//     }

//     fclose($output);
//     exit;
// }

function normalize_data($data) {
    if (is_string($data)) {
        $data = str_replace(["\r", "\n"], ' ', $data);  // Replace line breaks with spaces
        $data = trim($data);  // Trim any leading or trailing spaces
    }
    return $data;
}

function process_array_data($data) {
    if (is_array($data)) {
        return json_encode($data);  // Encode arrays to JSON for safe CSV output
    }
    return $data;  // Return the original data if it's not an array
}

function escape_special_characters($data) {
    if (is_string($data)) {
        // Ensure double quotes within fields are escaped properly
        $data = str_replace('"', '""', $data);
    }
    return $data;
}

function export_quiz_results_to_csv() {
    global $wpdb, $mlwQuizMasterNext;

    // Gets results data.
    $result_id    = isset($_GET["result_id"]) ? intval($_GET["result_id"]) : 0;
    $results_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}mlw_results WHERE result_id = %d", $result_id));

    if (!$results_data) {
        wp_die('No result data found.');
    }

    // Prepare plugin helper.
    $quiz_id = intval($results_data->quiz_id);
    $mlwQuizMasterNext->pluginHelper->prepare_quiz($quiz_id);

    // Prepare results array.
    $results = maybe_unserialize($results_data->quiz_results);
    if (!is_array($results)) {
        $results = array();
    }

    $results_array = apply_filters('mlw_qmn_template_variable_results_array', array(
        'quiz_id'                => $results_data->quiz_id,
        'quiz_name'              => $results_data->quiz_name,
        'quiz_system'            => $results_data->quiz_system,
        'form_type'              => $results_data->form_type,
        'user_name'              => $results_data->name,
        'user_business'          => $results_data->business,
        'user_email'             => $results_data->email,
        'user_phone'             => $results_data->phone,
        'user_id'                => $results_data->user,
        'timer'                  => isset($results[0]) ? $results[0] : 0,
        'time_taken'             => $results_data->time_taken,
        'total_points'           => $results_data->point_score,
        'total_score'            => $results_data->correct_score,
        'total_correct'          => $results_data->correct,
        'total_questions'        => $results_data->total,
        'comments'               => isset($results[2]) ? $results[2] : '',
        'question_answers_array' => isset($results[1]) ? $results[1] : array(),
        'contact'                => isset($results["contact"]) ? $results["contact"] : array(),
        'result_id'              => $result_id,
    ));

 

    // $questions     = QSM_Questions::load_questions_by_pages( $mlw_quiz_array['quiz_id'] );
    // $qmn_questions = array();
    // foreach ( $questions as $question ) {
    //     $qmn_questions[ $question['question_id'] ] = $question['question_answer_info'];
    // }

   //  // Cycles through each answer in the responses.
   //  $total_question_cnt = count( $mlw_quiz_array['question_answers_array'] );
   //  $qsm_question_cnt   = 1;
   //  foreach ( $mlw_quiz_array['question_answers_array'] as $answer ) {
   //      if ( ! empty( $hidden_questions ) && is_array( $hidden_questions ) && in_array( $answer['id'], $hidden_questions, true ) ) {
   //          continue;
   //      }
   //      $display .= qsm_questions_answers_shortcode_to_text( $mlw_quiz_array, $qmn_question_answer_template, $questions, $qmn_questions, $answer, $qsm_question_cnt, $total_question_cnt );
   //      $qsm_question_cnt++;
   //  }


   // // Handling question_answers_array
   //  $question_answers = isset($results[1]) ? $results[1] : array();
   //  $question_columns = [];
   //  foreach ($question_answers as $question_id => $answer_data) {
   //      $question_columns['question_' . $question_id] = isset($answer_data['question']) ? $answer_data['question'] : 'Unknown Question';
   //      $question_columns['answer_' . $question_id] = isset($answer_data['answer']) ? implode(', ', (array)$answer_data['answer']) : 'No Answer';
   //  }

    // // Merging the question columns into the main results array
    // $results_array = array_merge($results_array, $question_columns);

    // Convert arrays and complex data structures to strings (e.g., JSON)
    foreach ($results_array as $key => $value) {
        $results_array[$key] = normalize_data(process_array_data($value));
    }

    // Set CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=quiz-results.csv');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Output header row
    fputcsv($output, array_keys($results_array));

    // Output data row
    fputcsv($output, array_values($results_array));

    // // Output header row with explicit delimiter and enclosure
    // fputcsv($output, array_keys($results_array), ',', '"');

    // // Output data row with explicit delimiter and enclosure
    // fputcsv($output, array_values($results_array), ',', '"');

    fclose($output);
    exit;
}

// Hook the function to a WordPress action or call it directly
add_action('admin_post_export_quiz_results_to_csv', 'export_quiz_results_to_csv');


add_action('admin_notices', 'add_export_to_csv_button');
function add_export_to_csv_button($result_id) {
    $screen = get_current_screen();
    if ($screen->id == 'admin_page_qsm_quiz_result_details') {

        // Check if result_id is set in the query string
        if (!isset($_GET["result_id"])) {
            wp_die('Error: Missing result ID.');
        }
        // Get the result ID from the query parameter
        $result_id = intval($_GET["result_id"]);
    
        echo '<a href="' . admin_url('admin-post.php?action=export_quiz_results_to_csv&result_id=' . intval($result_id)) . '" class="button button-primary" style="margin-right: 15px;">' . esc_html__('Export to CSV', 'quiz-master-next') . '</a>';
    }
}


// // Add the export button to the QSM results page
// add_action('admin_notices', 'qsm_csv_export_button');

// function qsm_csv_export_button() {
//     $screen = get_current_screen();
//     // if ($screen->id == 'qsm_page_mlw_quiz_results') {
//     //     echo '<div class="notice notice-info"><p><a href="' . esc_url(admin_url('admin-post.php?action=export_qsm_csv')) . '" class="button button-primary">Export to CSV</a></p></div>';
//     // }
//     if ($screen->id == 'admin_page_qsm_quiz_result_details') {
//         // Place this code within your results details page code

//         // Check if result_id is set in the query string
//         if (!isset($_GET["result_id"])) {
//             wp_die('Error: Missing result ID.');
//         }
//         // Get the result ID from the query parameter
//         $result_id = intval($_GET["result_id"]);

//         echo '<div style="text-align:right; margin-top: 20px; margin-bottom: 20px;">';
//         echo '<h3 class="result-page-title">'.esc_html__('Quiz Result','quiz-master-next').' - '. esc_html( $results_data->quiz_name ) .'</h3>';

//         // Add the Export to CSV button
//         echo '<a href="' . admin_url('admin-post.php?action=export_quiz_results_to_csv&result_id=' . intval($result_id)) . '" class="button button-primary" style="margin-right: 15px;">' . esc_html__('Export to CSV', 'quiz-master-next') . '</a>';

//         // Add the Back to Results button
//         echo '<a style="margin-right: 15px;" href="?page=mlw_quiz_results" class="button button-primary" title="Return to results">'. esc_html__( 'Back to Results', 'quiz-master-next' ) .'</a>';

//         if (!is_null($previous_results) && $previous_results) {
//             echo "<a class='button button-primary' title='View Previous Result' href=\"?page=qsm_quiz_result_details&result_id=" . intval($previous_results) . "\" ><span class='dashicons dashicons-arrow-left-alt2'></span></a> ";
//         } else {
//             echo "<a class='button button-primary' title='View Previous Result' href='#' disabled=disabled><span class='dashicons dashicons-arrow-left-alt2'></span></a> ";
//         }

//         if (!is_null($next_results) && $next_results) {
//             echo " <a class='button button-primary' title='View Next Result' href=\"?page=qsm_quiz_result_details&result_id=" . intval($next_results) . "\" ><span class='dashicons dashicons-arrow-right-alt2'></span></a>";
//         } else {
//             echo " <a class='button button-primary' title='View Next Result' href='#' disabled=disabled><span class='dashicons dashicons-arrow-right-alt2'></span></a>";
//         }
//         echo '</div>';

//     }
// }


