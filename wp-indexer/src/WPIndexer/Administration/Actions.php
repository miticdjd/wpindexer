<?php

namespace WPIndexer\Administration;

use WPIndexer\Configuration\Options;
use WPIndexer\Elastic\Indexer;

class Actions
{
    /**
     * Settings array
     * 
     * @var array
     */
    private $settings;
    
    public function __construct()
    {
        /* Save post action */
        add_action('save_post', array(&$this, 'savePost'));
        
        /* Delete post action */
        add_action('delete_post', array(&$this, 'deletePost'));
        
        /* Trash post action */
        add_action('trash_post', array(&$this, 'deletePost'));
        
        $this->settings = Options::get('content_indexing');
    }
    
    /**
     * Save WP_Post action
     */
    public function savePost($postId)
    {
        if (is_object($postId)) {
            $post = $postId;
        } else {
            $post = get_post($postId);
        }

        if ($post == null || !in_array($post->post_type, $this->settings['posts'])) {
            return; // We are not going to update this type of post
        }
        
        /* Check for categories */
        $category = false;
        $postCategories = wp_get_post_categories($postId);
        foreach ($postCategories as $catId) {
            if (in_array($catId, $this->settings['categories'])) {
                $category = true;
            }
        }
        
        if (!$category) {
            return; // We are not going to update this post
        }
        
        $indexer = new Indexer();
        
        if ($post->post_status == 'publish') {
            $indexer->put($post);
        }
    }
    
    /**
     * Delete WP_Post action
     */
    public function deletePost()
    {
        if (is_object($postId)) {
            $post = $postId;
        } else {
            $post = get_post($postId);
        }

        if ($post == null || !in_array($post->post_type, $this->settings['posts'])) {
            return; // We are not going to update this type of post
        }

        $indexer = new Indexer();
        $indexer->remove($post);
    }
    
}