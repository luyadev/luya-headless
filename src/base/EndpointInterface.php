<?php
namespace luya\headless\base;

/**
 * Endpoint Interface.
 * 
 * Every endpoint defintion must implement this interface.
 * @since 1.0.0
 * @author Basil Suter <basil@nadar.io>
 */
interface EndpointInterface
{
    /**
     * Returns the name of the endpoint.
     * 
     * @return string
     */
    public function getEndpointName();
    
    /**
     * Process the endpoint response content (array parsed value).
     * 
     * This allows you to interact with the content, for example if the models
     * are wrapped into an enclosed array key like `'items' => []`. 
     * 
     * ```php
     * public function processContent(array $content)
     * {
     *     return $content['items'];
     * }
     * ```
     * 
     * @return array
     */
    public function processContent(array $content);
    
    /**
     * Create a get request.
     */
    public static function get();
    
    /**
     * Create a post request.
     */
    public static function post();
    
    /**
     * Create a put request.
     */
    public static function put();
    
    /**
     * Create a delete request.
     */
    public static function delete();
}

