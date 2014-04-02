<?php

namespace WPIndexer\Elastic;

use Elastica\Exception\NotFoundException;
use Elastica\Exception\ResponseException;
use Elastica\Document;

use WPIndexer\Configuration\Options;
use WPIndexer\Configuration\PostFields;

class Indexer
{
    /**
     * Settings for elastic search
     * @var array
     */
    private $settings;
    
    /**
     * Content indexing settings
     * @var array
     */
    private $contentIndexing;
    
    /**
     * Elastica client
     * @var object 
     */
    private $elastica;
    
    public function __construct()
    {
        /* Configuration */
        $configuration = Options::get();
        
        if (isset($configuration['settings'])) {
            /* Get configuration settings */
            $this->settings = $configuration['settings'];

            /* Get content indexingin settings */
            $this->contentIndexing = $configuration['content_indexing'];

            /* Create config for elastica */
            $config = array(
                'url' => $this->settings['server_url'],
            );

            /* Get elastica client */
            $this->elastica = new \Elastica\Client($config);
        } else {
            throw new \Exception('There is problem with connecting with elasticsearch.');
        }
    }
    
    /**
     * Get type
     * @param string $type
     */
    private function getType($type)
    {
        return $this->elastica->getIndex($this->settings['index_name'])->getType($type);
    }
    
    /**
     * Return elasticsearch status
     */
    public function getStatus()
    {
        return $this->elastica->getStatus();
    }
    
    /**
     * Combination of add and update from elastic search index
     * @param WP_Post $post Wordpress post we will try to add or update
     */
    public function put($post)
    {
        $document = $this->createDocument($post);
        
        $type = $this->getType($post->post_type);
        $type->addDocument(new Document($post->ID, $document));
    }
    
    /**
     * Remove document from elastic search index
     * @param WP_Post $post Wordpress pow we will try to remove
     */
    public function remove($post)
    {
        $type = $this->getType($post->post_type);

        try {
            $type->deleteById($post->ID);
        } catch (NotFoundException $ex) {
            // nothing for now
        }
    }
    
    /**
     * Remove all documents from elastic search index
     */
    public function removeAll()
    {
        $types = $this->contentIndexing['posts'];
        
        /* Delete all types from elasticsearch */
        foreach ($types as $type) {
            try {
                $this->getType($type)->delete();
            } catch (ResponseException $ex) {
                // nothing for now
            }
        }
        
        return true;
    }
    
    /**
     * Create elastic search document from our post
     * @param WP_Post $post
     */
    private function createDocument($post)
    {
        $postFields = new PostFields();
        $fields = $postFields->getAll();
        $document = array();
        foreach ($fields as $field => $desc) {
            if (isset($post->$field)) {
                if ($field == 'post_content') {
                    $document[$field] = strip_tags($post->$field);
                } else {
                    $document[$field] = $post->$field;
                }
            }
        }
        
        return $document;
    }
    
}