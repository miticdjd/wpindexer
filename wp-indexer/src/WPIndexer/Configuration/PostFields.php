<?php

namespace WPIndexer\Configuration;

class PostFields
{
    
    private $availableOptionalFields = array(
        'post_title' => 'Post title',
        'post_content' => 'Post content'
    );
    
    /**
     * Post fields
     * @var array
     */
    private $optionalFields = array();
    
    private $requiredFields = array(
        'post_date' => 'Post date'
    );
    
    public function __construct() {
        $contentIndexing = Options::get('content_indexing');
        $fields = isset($contentIndexing['fields']) ? $contentIndexing['fields'] : array();
        
        $optionalFields = array();
        foreach ($fields as $field) {
            $optionalFields[$field] = str_replace('_', ' ', ucfirst($field));
        }
        
        $this->optionalFields = $optionalFields; // Set optional fields
    }
    
    /**
     * Get all post fields
     * @return array
     */
    public function getAll()
    {
        return array_merge($this->optionalFields, $this->requiredFields);
    }
    
    /**
     * Get optional fields
     * @return array
     */
    public function getOptionalFields()
    {
        return $this->optionalFields;
    }
    
    /**
     * Return available optional fields
     * @return array
     */
    public function getAvailableOptionalFields()
    {
        return $this->availableOptionalFields;
    }
    
    /**
     * Get required fields
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->requiredFields;
    }
}