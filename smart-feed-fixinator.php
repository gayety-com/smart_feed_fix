<?php
/*
Plugin Name: Smart Feed Fixinator
Description: Forces full post content and adds the featured image to RSS <content:encoded> feeds.
Version: 1.0
Author: Jeff Kaufman
*/

add_filter('the_content_feed', 'gfi_force_full_content_in_feed', 10, 2);

function gfi_force_full_content_in_feed($content) {
    global $post;

    // Ensure we're inside a feed
    if (is_feed()) {
        // Debugging output
        error_log('RSS Feed is being generated for post ID: ' . $post->ID);

        // Get full post content
        $content = $post->post_content;

        // Apply necessary filters (shortcodes, embeds, etc.)
        $content = apply_filters('the_content', $content);

        // Debugging output for content
        error_log('Full Content: ' . $content);
		
		$featured_image_url = get_the_post_thumbnail_url($post, 'full');

        if ($featured_image_url) {
            // Prepend the featured image to the content
            $img_tag = '<p><img src="' . esc_url($featured_image_url) . '" alt="' . esc_attr(get_the_title($post)) . '" /></p>';
            $content = $img_tag . $content;
        }

        // Make sure the content is wrapped in CDATA for proper RSS format
        echo $content;
    }
}
