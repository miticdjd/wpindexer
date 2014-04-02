<?php

namespace WPIndexer\WP;

use WPIndexer\Configuration\Options;
use WPIndexer\Tracker\Keyword;
use WPIndexer\Elastic\Search as Searcher;

class Search
{
    /**
     * Status did we searched through elasticsearch
     * @var boolan
     */
    private $lanched = false;
    
    /**
     * Number of total results
     * @var int 
     */
    private $total;
    
    /**
     * Number of page that we need
     * @var int
     */
    private $page;
    
    /**
     * Ids of posts that we found in elasticsearch
     * @var array 
     */
    private $ids;
    
    public function __construct()
    {
        /* Get settings */
        $this->settings = Options::get('settings');
        
        /* Pre posts hook */
        add_action('pre_get_posts', array(&$this, 'find'));
        
        /* The posts hook */
        add_action('the_posts', array(&$this, 'process'));
    }
    
    public function find($query)
    {
        /* We need to check is this a main search and elasticsearch is enabled */
        if(!$query->is_main_query() || !is_search() || is_admin() || !$this->settings['enable_search']){
            return null;
        }
        
        /* Set page that we need results */
        $this->page = isset($query->query_vars['paged']) && $query->query_vars['paged'] > 0 ? $query->query_vars['paged'] - 1 : 0;

        /* We need to to set default posts per page in query_vars */
        if(!isset($query->query_vars['posts_per_page'])) {
                $query->query_vars['posts_per_page'] = get_option('posts_per_page');
        }

        /* Lunch searcher and last's find results */
        $searcher = new Searcher();
        $results = $searcher->find(str_replace('\"', '"', $query->query_vars['s']), $this->page, $query->query_vars['posts_per_page']);

        if($results == null) {
            /* If we don't have results we will return null */
            return null;
        }
        
        /* Change $wp_query */
        $query->query_vars['s'] = '';	
        $query->query_vars['post__in'] = empty($results['ids']) ? array() : $results['ids'];
        $query->query_vars['paged'] = 1;
        
        /* Set total number of posts thats match our search */
        $this->total = $results['total'];
        
        /* Set ids of posts that we are returning as results */
        $this->ids = $results['ids'];

        /* Set our flag to true */
        $this->lanched = true;
    }
    
    /**
     * Process results to wp_query
     * @global array $wp_query
     * @param array $posts
     * @return array
     */
    public function process($posts)
    {
        global $wp_query;

        if ($this->lanched) {
            /* Set values in wp_query */
            $this->lanched = false; // we are reseting our flag
            $wp_query->max_num_pages = ceil( $this->total / $wp_query->query_vars['posts_per_page'] );
            $wp_query->found_posts = $this->total;
            $wp_query->query_vars['paged'] = $this->page + 1;
            $wp_query->query_vars['s'] = isset($_GET['s']) ? str_replace('\"', '"', $_GET['s']): '';
        }

        /* return posts */
        return $posts;
    }

}