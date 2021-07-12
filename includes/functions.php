<?php
function trns_link_next_step($permalink, $cpt) {

    if ( $cpt->post_type !== 'sfwd-courses' ) { 
        return $permalink;
    }
	
	global $post;

    if (!get_option('trns_everywhere') && !has_shortcode($post->post_content, 'ld_profile') ) { 
        return $permalink;
    }
    
    $user = wp_get_current_user();
    if ( !$user ) { 
		return $permalink;
    }
    $user_id = $user->ID;
    $course_id = $cpt->ID;
    if ( !sfwd_lms_has_access($course_id, $user_id) ) { 
        return $permalink;
    }

    //Ok, user has acces to course. Let´s find the next step to be completed
    
	//from ld_course_resume.php
    $course_status = learndash_course_status( $course_id, $user_id, true );
    if ( 'completed' !== $course_status ) {
        $user_course_last_step_id = learndash_user_progress_get_first_incomplete_step( $user_id, $course_id );
        if ( ! empty( $user_course_last_step_id ) ) {
            $next_step_permalink = learndash_get_step_permalink( $user_course_last_step_id, $course_id );
            if ( ! empty( $next_step_permalink ) ) {
                return $next_step_permalink;        
            }
        }
    }
    return $permalink;
}
//callback for add_filter('post_type_link' , 'trns_link_next_step', 999, 2);