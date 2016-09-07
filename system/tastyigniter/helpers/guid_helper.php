<?php
/**
 * TastyIgniter
 *
 * An open source online ordering, reservation and management system for restaurants.
 *
 * @package   TastyIgniter
 * @author    SamPoyigi
 * @copyright TastyIgniter
 * @link      http://tastyigniter.com
 * @license   http://opensource.org/licenses/GPL-3.0 The GNU GENERAL PUBLIC LICENSE
 * @since     File available since Release 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * GUID helper functions
 *
 * @category       Helpers
 * @package        TastyIgniter\Helpers\TI_GUID_helper.php
 * @link           http://docs.tastyigniter.com
 */

if ( ! function_exists('GUID')) {
    /**
     * Get time elapsed
     *
     * The phunction PHP framework (http://sourceforge.net/projects/phunction/) uses the following function to generate valid version 4 UUIDs:
     *
     *
     * @return string GUID
     */
    function GUID() {
        $data = openssl_random_pseudo_bytes(16);
        
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

// ------------------------------------------------------------------------



/* End of file TI_GUID_helper.php */
/* Location: ./system/tastyigniter/helpers/TI_GUID_helper.php */