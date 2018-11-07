<?php

/**
 * Encapsulates the interface with the external service DOAJ.
 *
 * @link       http://example.com
 * @since      0.2.2+
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 */

/**
 * Encapsulates the interface with the external service DOAJ.
 *
 * Provides methods to interface with DOAJ.
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 * @author     Christian Gogolin <o3po@quantum-journal.org>
 */
class O3PO_Doaj {

        /**
         * Post meta-data to DOAJ
         *
         * Posts meta-data in json format to the DOAJ.
         *
         * @since:    0.2.2+
         * @access    public
         * @param     string  $doaj_json     Json encoded meta-data in a format suitable for DOAJ
         * @param     string  $doaj_api_url  The DOAJ API submission URL
         * @param     string  $doaj_api_key  The DOAJ API key
         */
    public static function remote_post_meta_data_to_doaj( $doaj_json, $doaj_api_url, $doaj_api_key ) {

            // Construct the HTTP POST call
		$headers = array( 'content-type' => 'application/json', 'accept' => 'application/json');
        $payload = $doaj_json;

        $doaj_api_url = $doaj_api_url;
        $doaj_api_url_with_key = $doaj_api_url . '?api_key=' . $doaj_api_key;

        $response = wp_remote_post( $doaj_api_url_with_key, array(
                                        'headers' => $headers,
                                        'body' => $payload,
                                        'method'    => 'POST'
                                                                  ) );

        return $response;
    }
}