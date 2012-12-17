<?php
/*
	Name: Thesis 2 Template Fix
	Author: Tim Milligan
	Description: Template Fix box for Plugins that use Custom Templates
	Version: 1.0
	Class: t2_template_fix
*/

class t2_template_fix extends thesis_box {
	protected function translate() {
		$this->title = __('Template Fix', 't2tf');
	}
	
	public function construct() {
		add_action('thesis_init_post_meta', 'template_fix');  
	
	}
	
	function template_fix() {
		apply_filters('template_include');
	}
	
	public function html($args = false) {
		global $thesis, $wp_query, $post;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$post_count = 1;
		if (!have_posts() && $wp_query->is_404)
			$wp_query = apply_filters('thesis_404', $wp_query);
		if (have_posts())
			while (have_posts()) {
				the_post();
				if (!$wp_query->is_singular)
					do_action('thesis_init_post_meta', $post->ID);
				$schema = !empty($schema) ? ' itemprop="' . ($schema == 'article' ? 'articleBody' : 'text') . '"' : '';
				echo "$tab<div class=\"post_content" . (!empty($this->options['class']) ? ' ' . trim($thesis->api->esc($this->options['class'])) : '') . "\"$schema>\n";
				do_action('thesis_hook_before_post');
				the_content(trim($thesis->api->escht(!empty($this->post_meta['read_more']) ? #wp
					$this->post_meta['read_more'] : (!empty($this->options['read_more']) ?
					$this->options['read_more'] :
					$this->read_more), true)));
				if ($wp_query->is_singular) wp_link_pages("<p><strong>{$thesis->api->strings['pages']}:</strong> ", '</p>', 'number'); #wp
				do_action('thesis_hook_after_post');
				echo "$tab</div>\n";
				$post_count++;
			}
		elseif (!$wp_query->is_404)
			do_action('thesis_empty_loop');
	}
	
}