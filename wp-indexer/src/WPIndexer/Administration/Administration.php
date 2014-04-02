<?php

namespace WPIndexer\Administration;

use WPIndexer\Configuration\Options;
use WPIndexer\Configuration\Pages;
use WPIndexer\Configuration\PostFields;

use WPIndexer\Validator\Rules;
use WPIndexer\Validator\Post;

use WPIndexer\Elastic\Indexer;

class Administration
{
    /**
     * Errors we are going to display to page
     * @var array
     */
    private $errors = array();
    
    /**
     * Update was successfully so display message
     * @var boolean
     */
    private $success = false;
    
    /**
     * Values forms
     * @var array
     */
    private $values = array();
    
    /**
     * Data we are using in views
     * @var array|mixed 
     */
    private $data = array();
    
    
    public function __construct()
    {
        /* Register menu */
        add_action('admin_menu', array(&$this, 'createMenu'));
        
        /* Load javascript */
        add_action('admin_footer', array(&$this, 'myJavascript'));
        
        /* Setup ajax calls */
        add_action('wp_ajax_wp_indexer_wipe_data', array(&$this, 'wipeData'));
        add_action('wp_ajax_wp_indexer_re_index_data', array(&$this, 'reIndexData'));
    }
    
    /**
     * Create administrator menu in wp-admin
     */
    public function createMenu()
    {
        $pagesConfig = new Pages();
        $pages = $pagesConfig->getPages();
        $subpages = $pagesConfig->getSubpages();
        
        /* Register main pages */
        foreach ($pages as $page) {
            add_menu_page(
                $page['page_title'], 
                $page['menu_title'], 
                $page['cap'], 
                $page['slug'],
                array(&$this, $page['fnc'])
            );
        }
        
        /* Register subpages */
        foreach ($subpages as $page) {
            add_submenu_page(
                $page['parent'],
                $page['page_title'], 
                $page['menu_title'], 
                $page['cap'], 
                $page['slug'],
                array(&$this, $page['fnc'])
            );
        }
    }
    
    /**
     * Load javascript for administration
     */
    public function myJavascript()
    {
        $this->loadView('javascript');
    }
    
    /**
     * Display settings page
     */
    public function wpIndexerSettings()
    {
        if (isset($_POST['submit'])) {
            /* If use submited form update settings */
            $this->updateSettings();
        } else {
            $this->values = Options::get('settings');
        }
        
        $this->loadView('settings');
    }
    
    /**
     * Display page for content indexing
     */
    public function wpIndexerContentIndexing()
    {
        /* Get categories */
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'asc',
            'hide_empty' => 0
        ));
        
        /* Get post types */
        $posts = get_post_types(array('public' => true));
        
        /* Get post fields */
        $postFields = new PostFields();
        $fields = $postFields->getAvailableOptionalFields();
        
        if (isset($_POST['submit'])) {
            /* If use submited form update settings */
            $this->updateContentIndexing();
        } else {
            $this->values = Options::get('content_indexing');
        }
        
        $this->data = array(
            'categories' => !is_null($categories) ? $categories : array(),
            'posts' => $posts,
            'fields' => $fields
        );
        
        $this->loadView('contentIndexing');
    }
    
    /**
     * Display page for managing index
     */
    public function wpIndexerManageIndex()
    {
        $this->loadView('manageIndex');
    }
    
    /**
     * Update settings in database
     */
    private function updateSettings()
    {
        /* Rules for validation */
        $rules = new Rules();
        $rules->set('enable_search', null)
            ->set('server_url', 'req|text')
            ->set('index_name', 'req|text')
            ->set('read_timeout', 'req|int')
            ->set('write_timeout', 'req|int');
        
        $post = new Post($rules->get());
        if ($post->isValid()) {
            /* Everything is good, we can update settings */
            Options::update(array('settings' => $post->getData()));
            
            /* Let's now validate are we have valid data */
            try {
                $indexer = new Indexer();
                $status = $indexer->getStatus();
                $info = $status->getResponse()->getTransferInfo();
            } catch (\Elastica\Exception\ClientException $ex) {
                $info['http_code'] = '400';
            }
            
            if ($info['http_code'] != '200') {
                Options::update(array('settings' => array()));
                $this->errors = array(
                    'There was problem with connecting to elasticsearch, please check if you enter valid informations!'
                );
            } else {
                $this->success = true;
            }
        } else {
            $this->errors = $post->getErrors();
        }
        
        $this->values = $post->getData();
    }
    
    /**
     * Update content indexing
     */
    private function updateContentIndexing()
    {
        $options = array(
            'content_indexing' => array(
                'categories' => isset($_POST['categories']) ? $_POST['categories'] : array(),
                'posts' => isset($_POST['posts']) ? $_POST['posts'] : array(),
                'fields' => isset($_POST['fields']) ? $_POST['fields'] : array()
            )
        );
        
        Options::update($options);
        $this->success = true;
        $this->values = $options['content_indexing'];
    }
    
    /**
     * Wipe data from elasticsearch
     */
    public function wipeData()
    {
        try {
            $indexer = new Indexer();
        
            if ($indexer->removeAll()) {
                echo json_encode(array('status' => 200, 'msg' => 'All data is deleted!'));
            } else {
                echo json_encode(array('status' => 400, 'msg' => 'There was some problem!'));
            }
        } catch (\Exception $ex) {
            echo json_encode(array('status' => 400, 'msg' => $ex->getMessage()));
        }
        
        die(); // needs to use this because of wordpress :(
    }
    
    /**
     * Re index all posts to elasticsearch
     */
    public function reIndexData()
    {
        $manageIndex = Options::get('content_indexing');
        $types = isset($manageIndex['posts']) ? $manageIndex['posts'] : array();
        
        try {
            $indexer = new Indexer();
        } catch (\Exception $ex) {
            echo json_encode(array('status' => 400, 'msg' => $ex->getMessage()));
            die(); // needs to use this because of wordpress :(
        }
        
        foreach ($types as $type) {
            $args = array(
                'posts_per_page' => -1,
                'offset' => 0,
                'orderby' => 'post_date',
                'order' => 'ASC',
                'post_type' => $type,
                'post_status' => 'publish',
                'suppress_filters' => true
            );
            
            $posts = get_posts($args);
            
            foreach ($posts as $post) {
                /* Check for categories */
                $category = false;
                $postCategories = wp_get_post_categories($post->ID);
                foreach ($postCategories as $catId) {
                    if (in_array($catId, $manageIndex['categories'])) {
                        $category = true;
                    }
                }

                if ($category) {
                    $indexer->put($post); // we will update this type of post
                }
            }
        }
        
        echo json_encode(array('status' => 200, 'msg' => 'All data is indexed in elasticsearch index!'));
        
        die(); // needs to use this because of wordpress :(
    }
    
    /**
     * Load view for pages
     * @param string $fileName file name of template we want to load
     */
    private function loadView($fileName)
    {
        if (file_exists(WPINDEXER_PATH . 'src/WPIndexer/Resources/views/' . $fileName . '.php')) {
            return require WPINDEXER_PATH . 'src/WPIndexer/Resources/views/' . $fileName . '.php';
        }
    }

}