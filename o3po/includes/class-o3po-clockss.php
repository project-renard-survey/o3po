<?php

/**
 * Encapsulates the interface with the external service CLOCKSS.
 *
 * @link       http://example.com
 * @since      0.2.2+
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 */

/**
 * Encapsulates the interface with the external service CLOCKSS.
 *
 * Provides methods to interface with CLOCKSS.
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 * @author     Christian Gogolin <o3po@quantum-journal.org>
 */
class O3PO_Clockss {

        /**
         *
         *
         */
    public static function ftp_upload_meta_data_and_pdf_to_clockss( $clockss_xml, $pdf_path, $remote_filename_without_extension, $clockss_ftp_url, $clockss_username, $clockss_password ) {

                $trackErrors = ini_get('track_errors');
        $ftp_connection = null;
        $clockss_response = '';
        try
        {
            set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
                    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
                });

            $tmpfile_clockss_xml = tempnam(sys_get_temp_dir(), $remote_filename_without_extension );
            file_put_contents($tmpfile_clockss_xml, $clockss_xml);

            $ftp_connection = ftp_connect($clockss_ftp_url);
            $login_result = ftp_login($ftp_connection, $clockss_username, $clockss_password);

            if (ftp_put($ftp_connection, $remote_filename_without_extension . '.xml', $tmpfile_clockss_xml, FTP_BINARY))
                $clockss_response .= "INFO: successfully uploaded the meta-data xml to CLOCKSS.\n";
            else
                $clockss_response .= "ERROR: There was an error uploading the meta-data xml to CLOCKSS: " . $php_errormsg . "\n";

            if (ftp_put($ftp_connection, $remote_filename_without_extension . '.pdf', $pdf_path, FTP_BINARY))
                $clockss_response .= "INFO: successfully uploaded the fulltext pdf to CLOCKSS.\n";
            else
                $clockss_response .= "ERROR: There was an error uploading the fulltext pdf to CLOCKSS: " . $php_errormsg . "\n";


        } catch(Exception $e) {
            $clockss_response .= "ERROR: There was an exception during the ftp transfer to CLOCKSS. " . $e->getMessage() . "\n";
        } finally {
            ini_set('track_errors', $trackErrors);
            restore_error_handler();
            if($ftp_connection !== null)
                try
                {
                    ftp_close($ftp_connection);
                } catch(Exception $e)
                    $clockss_response .= "ERROR: There was an exception while closing the ftp connection to CLOCKSS. " . $e->getMessage() . "\n";
        }

        return $clockss_response;
    }
}
