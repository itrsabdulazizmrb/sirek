<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Function to add views column to blog_posts table if it doesn't exist
 */
function add_views_column_to_blog_posts() {
    $CI =& get_instance();
    
    // Check if the column exists
    $CI->load->database();
    $table_exists = $CI->db->table_exists('blog_posts');
    
    if ($table_exists) {
        // Check if views column exists
        $fields = $CI->db->field_data('blog_posts');
        $views_column_exists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'views') {
                $views_column_exists = true;
                break;
            }
        }
        
        // If views column doesn't exist, add it
        if (!$views_column_exists) {
            $CI->load->dbforge();
            $fields = array(
                'views' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                    'after' => 'status'
                )
            );
            
            $CI->dbforge->add_column('blog_posts', $fields);
            
            // Log that the column was added
            log_message('info', 'Added views column to blog_posts table');
        }
    }
}
