<?php

namespace Kanboard\Core\Ldap;

/**
 * LDAP Query
 *
 * @package ldap
 * @author  Frederic Guillot
 */
class Query
{
    /**
     * LDAP client
     *
     * @access private
     * @var Client
     */
    private $client = null;

    /**
     * Query result
     *
     * @access private
     * @var array
     */
    private $entries = array();

    /**
     * Constructor
     *
     * @access public
     * @param  Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute query
     *
     * @access public
     * @param  string    $baseDn
     * @param  string    $filter
     * @param  array     $attributes
     * @return Query
     */
    public function execute($baseDn, $filter, array $attributes)
    {
        $sr = ldap_search($this->client->getConnection(), $baseDn, $filter, $attributes);
        if ($sr === false) {
            return $this;
        }

        $entries = ldap_get_entries($this->client->getConnection(), $sr);
        if ($entries === false || count($entries) === 0 || $entries['count'] == 0) {
            return $this;
        }

        $this->entries = $entries;

        return $this;
    }

    /**
     * Return true if the query returned a result
     *
     * @access public
     * @return boolean
     */
    public function hasResult()
    {
        return ! empty($this->entries);
    }

    /**
     * Get LDAP Entries
     *
     * @access public
     * @return Entities
     */
    public function getEntries()
    {
        return new Entries($this->entries);
    }
}
