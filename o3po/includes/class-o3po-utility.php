<?php

/**
 * A collection of various utility functions.
 *
 * @link       http://example.com
 * @since      0.1.0
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 */

/**
 * A collection of various utility functions.
 *
 * @since      0.1.0
 * @package    O3PO
 * @subpackage O3PO/includes
 * @author     Christian Gogolin <o3po@quantum-journal.org>
 */
class O3PO_Utility
{

        /**
         * Check that the given ORCID is well formated.
         *
         * Verifies whether the given ORCID has a valid checksum.
         *
         * Return true if the $orcid is a valid ORCID and otherwise a string
         * describing why it is not valid.
         *
         * @since    0.1.0
         * @access   public
         * @param    string     $orcid   ORCID to check
         * */
    static public function check_orcid( $orcid ) {

        if( !preg_match( '/([0-9]{4}-){3}[0-9]{3}[0-9X]/', $orcid) )
            return "is malformed";
        else {
            $orcid_digits = preg_replace('/-/', '', $orcid );
            $strlen = strlen( $orcid_digits );
            $total = 0;
            for( $i = 0; $i < $strlen-1; $i++ ) {
                $digit = (int)substr( $orcid_digits, $i, 1 );
                $total = ($total + $digit) * 2;
            }
            $remainder = $total % 11;
            $result = (12 - $remainder) % 11;
            $result = $result === 10 ? "X" : $result;
            $ok = substr( $orcid_digits, $strlen-1, 1 ) == $result;

            if($ok)
                return true;
            else
                return "did not pass the checksum test with result=" . $result . " but last digit=" . substr( $orcid_digits, $strlen-1, 1 );
        }
    }

        /**
         * Arbitrary precision base_convert().
         *
         * Similar to PHPs base_convert() but with arbitrary precision. Taken from http://php.net/manual/en/function.base-convert.php.
         *
         * @since    0.1.0
         * @access   public
         * @param    string    $str          String representation of the number to covert.
         * @param    int       $frombase     Base the input number is represented in.
         * @param    int       $tobase       Base to with the number is to be converted.
         * */
    static public function base_convert_arbitrary_precision( $str, $frombase, $tobase ) {

        $str = trim($str);

        if(intval($str) === 0)
            return "0";

        if(intval($frombase) != 10) {
            $len = strlen($str);
            $q = 0;
            for ($i=0; $i<$len; $i++) {
                $r = base_convert($str[$i], $frombase, 10);
                $q = bcadd(bcmul($q, $frombase), $r);
            }
        }
        else
            $q = $str;

        if (intval($tobase) != 10) {
            $s = '';
            while (bccomp($q, '0', 0) > 0) {
                $r = intval(bcmod($q, $tobase));
                $s = base_convert($r, 10, $tobase) . $s;
                $q = bcdiv($q, $tobase, 0);
            }
        }
        else
            $s = $q;

        return $s;
    }

        /**
         * Array of stopwords.
         *
         * @since    0.1.0
         * @access   public
         * @var      array   $stop_words     Array of stopwords.
         * */
    static public $stop_words = array('a','an','the','on','for','with', 'at', 'by', 'and');

        /**
         * Remove stopwords.
         *
         * Removes the stopwords in static::stop_words from $text and
         * tries to avoid the creation of double spaces.
         *
         * @since    0.1.0
         * @access   public
         * @param    string    $text    Text from which stopwords are to be removed.
         * */
    static public function remove_stopwords( $text ) {

        foreach (static::$stop_words as $target) {
            $text = preg_replace('#\\b( ' . $target . '|' . $target . ' |' . $target . ')\\b#i', '', $text);
        }

        return $text;
    }

        /**
         * Implode an array of words according to the oxford comma rule.
         *
         * @since    0.1.0
         * @access   public
         * @param    array    $array   Array of strings to be imploded with Oxford commas.
         * */
    static public function oxford_comma_implode( $array ) {

        if(empty($array))
            return "";
        if(count($array)==1)
            return end($array);

        $string = "";
        for ($x = 0; $x < count($array); $x++) {
            $string .= $array[$x];
            if( $x < count($array)-1 and count($array) > 2) $string .= ",";
            if( $x < count($array)-1 ) $string .= " ";
            if( $x == count($array)-2 ) $string .= "and ";
        }

        return $string;
    }

        /**
         * Make slashes breakable in text for html.
         *
         * There is no way to break long lines with css or html that works
         * consistently in all browsers. We are hence making long urls
         * breakable by adding zero width space characters afert every /.
         * This does not seem to have any adverse side-effects as the
         * zero-width characters are ignored when a link text is copied and
         * pasted.
         *
         * @since    0.1.0
         * @access   public
         * @param    string    $string     String in which slashes are to be made breakable.
         * */
    static public function make_slash_breakable_html( $string ) {
        $zero_width_space = html_entity_decode('&#8203;', 0, 'UTF-8');
        return preg_replace('#/#', '/' . $zero_width_space, $string );
    }

        /**
         * Verify that an email is well formed.
         *
         * Based on https://stackoverflow.com/questions/201323/how-to-validate-an-email-address-using-a-regular-expression/1917982#1917982
         *
         * @since 0.2.0
         * @param string    $email    Email to be checked
         */
    static function valid_email( $email )
    {
        return( preg_match(
            "/(?(DEFINE)
   (?<address>         (?&mailbox) | (?&group))
   (?<mailbox>         (?&name_addr) | (?&addr_spec))
   (?<name_addr>       (?&display_name)? (?&angle_addr))
   (?<angle_addr>      (?&CFWS)? < (?&addr_spec) > (?&CFWS)?)
   (?<group>           (?&display_name) : (?:(?&mailbox_list) | (?&CFWS))? ;
                                          (?&CFWS)?)
   (?<display_name>    (?&phrase))
   (?<mailbox_list>    (?&mailbox) (?: , (?&mailbox))*)

   (?<addr_spec>       (?&local_part) \@ (?&domain))
   (?<local_part>      (?&dot_atom) | (?&quoted_string))
   (?<domain>          (?&dot_atom) | (?&domain_literal))
   (?<domain_literal>  (?&CFWS)? \[ (?: (?&FWS)? (?&dcontent))* (?&FWS)?
                                 \] (?&CFWS)?)
   (?<dcontent>        (?&dtext) | (?&quoted_pair))
   (?<dtext>           (?&NO_WS_CTL) | [\x21-\x5a\x5e-\x7e])

   (?<atext>           (?&ALPHA) | (?&DIGIT) | [!#\$%&'*+-\\/=?^_`{|}~])
   (?<atom>            (?&CFWS)? (?&atext)+ (?&CFWS)?)
   (?<dot_atom>        (?&CFWS)? (?&dot_atom_text) (?&CFWS)?)
   (?<dot_atom_text>   (?&atext)+ (?: \. (?&atext)+)*)

   (?<text>            [\x01-\x09\x0b\x0c\x0e-\x7f])
   (?<quoted_pair>     \\ (?&text))

   (?<qtext>           (?&NO_WS_CTL) | [\x21\x23-\x5b\x5d-\x7e])
   (?<qcontent>        (?&qtext) | (?&quoted_pair))
   (?<quoted_string>   (?&CFWS)? (?&DQUOTE) (?:(?&FWS)? (?&qcontent))*
                        (?&FWS)? (?&DQUOTE) (?&CFWS)?)

   (?<word>            (?&atom) | (?&quoted_string))
   (?<phrase>          (?&word)+)

   # Folding white space
   (?<FWS>             (?: (?&WSP)* (?&CRLF))? (?&WSP)+)
   (?<ctext>           (?&NO_WS_CTL) | [\x21-\x27\x2a-\x5b\x5d-\x7e])
   (?<ccontent>        (?&ctext) | (?&quoted_pair) | (?&comment))
   (?<comment>         \( (?: (?&FWS)? (?&ccontent))* (?&FWS)? \) )
   (?<CFWS>            (?: (?&FWS)? (?&comment))*
                       (?: (?:(?&FWS)? (?&comment)) | (?&FWS)))

   # No whitespace control
   (?<NO_WS_CTL>       [\x01-\x08\x0b\x0c\x0e-\x1f\x7f])

   (?<ALPHA>           [A-Za-z])
   (?<DIGIT>           [0-9])
   (?<CRLF>            \x0d \x0a)
   (?<DQUOTE>          \")
   (?<WSP>             [\x20\x09])
 )

 (?&address)/x", $email));
    }

}
