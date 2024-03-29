<?php

 /**
 * Implementation of IServiceProvider.
 *
 * PHP version 5.3
 *
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   SVN: 1.0
 * @link      http://odataphpproducer.codeplex.com
 * 
 */
use ODataProducer\Configuration\EntitySetRights;
require_once 'ODataProducer/IDataService.php';
require_once 'ODataProducer/IRequestHandler.php';
require_once 'ODataProducer/DataService.php';
require_once 'ODataProducer/IServiceProvider.php';
use ODataProducer\Configuration\DataServiceProtocolVersion;
use ODataProducer\Configuration\DataServiceConfiguration;
use ODataProducer\IServiceProvider;
use ODataProducer\DataService;

require_once 'InventoryMetadata.php';
require_once 'InventoryQueryProvider.php';
require_once 'InventoryDSExpressionProvider.php';

/**
 * InventoryDataService that implements IServiceProvider.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class InventoryDataService extends DataService implements IServiceProvider
{
    private $_InventoryMetadata = null;
    private $_InventoryQueryProvider = null;
    private $_InventoryExpressionProvider = null;
    
    /**
     * This method is called only once to initialize service-wide policies
     * 
     * @param DataServiceConfiguration &$config Data service configuration object
     * 
     * @return void
     */
    public function initializeService(DataServiceConfiguration &$config)
    {   
        $config->setEntitySetPageSize('*', 10);
        $config->setEntitySetAccessRule('*', EntitySetRights::ALL);
        $config->setAcceptCountRequests(true);
        $config->setAcceptProjectionRequests(true);
        $config->setMaxDataServiceVersion(DataServiceProtocolVersion::V3);
    }

    /**
     * Get the service like IDataServiceMetadataProvider, IDataServiceQueryProvider,
     * IDataServiceStreamProvider
     * 
     * @param String $serviceType Type of service IDataServiceMetadataProvider, 
     *                            IDataServiceQueryProvider,
     *                            IDataServiceStreamProvider
     * 
     * @see library/ODataProducer/ODataProducer.IServiceProvider::getService()
     * @return object
     */
    public function getService($serviceType)
    {
        
        if (($serviceType === 'IDataServiceMetadataProvider') 
            || ($serviceType === 'IDataServiceQueryProvider2') 
            || ($serviceType === 'IDataServiceStreamProvider')
        ) {
            if (is_null($this->_InventoryExpressionProvider)) {
                $this->_InventoryExpressionProvider = new InventoryDSExpressionProvider();
            }
        }
        
        if ($serviceType === 'IDataServiceMetadataProvider') {
            if (is_null($this->_InventoryMetadata)) {
                $this->_InventoryMetadata = CreateInventoryMetadata::create();
            }
            return $this->_InventoryMetadata;
        } else if ($serviceType === 'IDataServiceQueryProvider2') {
            if (is_null($this->_InventoryQueryProvider)) {
                $this->_InventoryQueryProvider = new InventoryQueryProvider();
            }
            return $this->_InventoryQueryProvider;
        } else if ($serviceType === 'IDataServiceStreamProvider') {
            return new InventoryStreamProvider();
        }
        return null;
    }
}

?>
