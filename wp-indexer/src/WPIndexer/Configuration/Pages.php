<?php

namespace WPIndexer\Configuration;

class Pages 
{
    /**
     * List of all pages in this plugin
     * @var type 
     */
    private $pages = array(
        array(
            'page_title' => 'WPIndexer',
            'menu_title' => 'WPIndexer',
            'cap' => 'administrator',
            'slug' => 'wp-indexer-settings',
            'fnc' => 'wpIndexerSettings',
        )
    );
    
    /**
     * List of all sub pages in this plugin
     * @var type 
     */
    private $subPages = array(
        array(
            'parent' => 'wp-indexer-settings',
            'page_title' => 'Settings',
            'menu_title' => 'Settings',
            'cap' => 'administrator',
            'slug' => 'wp-indexer-settings',
            'fnc' => 'wpIndexerSettings',
        ),
        array(
            'parent' => 'wp-indexer-settings',
            'page_title' => 'Content indexing',
            'menu_title' => 'Content indexing',
            'cap' => 'administrator',
            'slug' => 'wp-indexer-content-indexing',
            'fnc' => 'wpIndexerContentIndexing',
        ),
        array(
            'parent' => 'wp-indexer-settings',
            'page_title' => 'Manage index',
            'menu_title' => 'Manage index',
            'cap' => 'administrator',
            'slug' => 'wp-indexer-manage-index',
            'fnc' => 'wpIndexerManageIndex',
        )
    );
    
    /**
     * Return list of all pages
     * @return array
     */
    public function getPages()
    {
        return $this->pages;
    }
    
    /**
     * Return list of all subpages
     * @return array
     */
    public function getSubpages()
    {
        return $this->subPages;
    }
}