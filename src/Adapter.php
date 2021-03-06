<?php
namespace Anam\PhantomMagick;

use Exception;
use InvalidArgumentException;

class Adapter
{
    /**
     * The driver of the Filesystem
     *
     * @var string
     */
    protected $driver = 'local';

    /**
     * Filesystem client. S3|Dropbox|Rackspace.
     *
     * @var mixed
     */
    protected $client;

    /**
     * Client related options
     *
     * @var array
     */
    protected $args;

    /**
     * Constructor
     *
     * @param mixed $client
     * @param array  $args
     */
    public function __construct($client, array $args = array())
    {
        $this->client = $client;
        $this->args = $args;
    }

    /**
     * Set the Filesystem driver
     *
     * @param string $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the driver
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Determine which Flysystem adapter required
     *
     * @return mixed
     */
    public function pick()
    {


        // Dropbox
        if ($this->client instanceof \Dropbox\Client) {
            $this->setDriver('dropbox');

            return $this->dropbox();
        }

        // Rackspace cloudFiles
        if ($this->client instanceof \OpenCloud\ObjectStore\Resource\Container) {
            $this->setDriver('rackspace');

            return $this->rackspace();
        }
    }



    /**
     * Create a new DropboxAdapter instance.
     *
     * @return \League\Flysystem\Dropbox\DropboxAdapter
     */
    public function dropbox()
    {
        $prefix = null;

        if (isset($this->args[0])) {
            $prefix = $this->args[0];
        }

        return new \League\Flysystem\Dropbox\DropboxAdapter($this->client, $prefix);
    }

    /**
     * Create a new RackspaceAdapter instance.
     *
     * @return \League\Flysystem\Rackspace\RackspaceAdapter
     */
    public function rackspace()
    {
        return new \League\Flysystem\Rackspace\RackspaceAdapter($this->client);
    }
}
