<?php

namespace WPIndexer\Elastic;

use WPIndexer\Configuration\Options;

use Elastica\Client;
use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\Search as ElasticSearch;

class Search {

    /**
     * Settings of elasticsearch
     * @var array
     */
    private $settings;
    
    /**
     * Instance of elasticsearch
     * @var object
     */
    private $elastica;
    
    public function __construct() {
        
        /* Configuration */
        $configuration = Options::get();
        
        /* Get configuration settings */
        $this->settings = $configuration['settings'];
        
        /* Create config for elastica */
        $config = array(
            'url' => $this->settings['server_url'],
        );
        
        /* Get elastica client */
        $this->elastica = new Client($config);
    }

    /**
     * Find results in elastic search
     * @param string $search
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function find($search, $page = 0, $limit = 10) {
        /* Create query */
        $query = $this->createQuery($search);
        /* Search for results in elasticsearch */
        $results = $this->search($query, $page, $limit);
        
        return $results;
    }
    
    /**
     * 
     * @param string $search
     * @return \Elastica\Query\QueryString
     */
    private function createQuery($search)
    {
        $tearm = new QueryString();
        $tearm->setQuery($search . '*');
        $tearm->setDefaultOperator('and');
        
        return $tearm;
    }
    
    /**
     * Search for results in 
     * @param \Elastica\Query $query
     * @param type $page
     * @param type $limit
     * @return array
     */
    private function search($query, $page, $limit)
    {
        $query = new Query($query);
        $query->setFrom($page * $limit);
        $query->setSize($limit);
        $query->setFields(array('id'));
        $query->addSort('_score');

        try {
            $index = $this->elastica->getIndex($this->settings['index_name']);

            $search = new ElasticSearch($index->getClient());
            $search->addIndex($index);
            $results = $search->search($query);

            return $this->parser($results);
        } catch (\Exception $ex) {
            return null;
        }
    }
    
    /**
     * Parse results from elastic search
     * @param object $response
     * @return array
     */
    private function parser($response)
    {
        $ids = array();
        foreach($response->getResults() as $result){
            $ids[] = $result->getId();
        }
        
        return array(
            'total' => $response->getTotalHits(), 
            'ids' => $ids
        );
    }
}
