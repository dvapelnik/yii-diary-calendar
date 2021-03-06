<?php
/**
 * A port of [phputf8](http://phputf8.sourceforge.net/) to a unified set
 * of files. Provides multi-byte aware replacement string functions.
 * For UTF-8 support to work correctly, the following requirements must be met:
 * - PCRE needs to be compiled with UTF-8 support (--enable-utf8)
 * - Support for [Unicode properties](http://php.net/manual/reference.pcre.pattern.modifiers.php)
 *   is highly recommended (--enable-unicode-properties)
 * - UTF-8 conversion will be much more reliable if the
 *   [iconv extension](http://php.net/iconv) is loaded
 * - The [mbstring extension](http://php.net/mbstring) is highly recommended,
 *   but must not be overloading string functions
 * [!!] This file is licensed differently from the rest of Kohana. As a port of
 * [phputf8](http://phputf8.sourceforge.net/), this file is released under the LGPL.
 * @package    Kohana
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class UTF8
{

    /**
     * @var  boolean  Does the server support UTF-8 natively?
     */
    public static $server_utf8 = null;

    /**
     * @var  array  List of called methods that have had their required file included.
     */
    public static $called = array();

    /**
     * @var  string
     */
    public static $charset = 'utf8';

    /**
     * Recursively cleans arrays, objects, and strings. Removes ASCII control
     * codes and converts to the requested charset while silently discarding
     * incompatible characters.
     *     static::clean($_GET); // Clean GET data
     * [!!] This method requires [Iconv](http://php.net/iconv)
     * @param   mixed $var        variable to clean
     * @param   string $charset    character set, defaults to static::$charset
     * @return  mixed
     * @uses    static::strip_ascii_ctrl
     * @uses    static::is_ascii
     */
    public static function clean($var, $charset = null)
    {
        if(!$charset)
        {
            // Use the application character set
            $charset = static::$charset;
        }

        if(is_array($var) OR is_object($var))
        {
            foreach($var as $key => $val)
            {
                // Recursion!
                $var[static::clean($key)] = static::clean($val);
            }
        } elseif(is_string($var) AND $var !== '')
        {
            // Remove control characters
            $var = static::strip_ascii_ctrl($var);

            if(!static::is_ascii($var))
            {
                // Disable notices
                $error_reporting = error_reporting(~E_NOTICE);

                // iconv is expensive, so it is only used when needed
                $var = iconv($charset, $charset . '//IGNORE', $var);

                // Turn notices back on
                error_reporting($error_reporting);
            }
        }

        return $var;
    }

    /**
     * Tests whether a string contains only 7-bit ASCII bytes. This is used to
     * determine when to use native functions or UTF-8 functions.
     *     $ascii = static::is_ascii($str);
     * @param   mixed $str    string or array of strings to check
     * @return  boolean
     */
    public static function is_ascii($str)
    {
        if(is_array($str))
        {
            $str = implode($str);
        }

        return !preg_match('/[^\x00-\x7F]/S', $str);
    }

    /**
     * Strips out device control codes in the ASCII range.
     *     $str = static::strip_ascii_ctrl($str);
     * @param   string $str    string to clean
     * @return  string
     */
    public static function strip_ascii_ctrl($str)
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
    }

    /**
     * Strips out all non-7bit ASCII bytes.
     *     $str = static::strip_non_ascii($str);
     * @param   string $str    string to clean
     * @return  string
     */
    public static function strip_non_ascii($str)
    {
        return preg_replace('/[^\x00-\x7F]+/S', '', $str);
    }

    /**
     * Replaces special/accented UTF-8 characters by ASCII-7 "equivalents".
     *     $ascii = static::transliterate_to_ascii($utf8);
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str    string to transliterate
     * @param   integer $case   -1 lowercase only, +1 uppercase only, 0 both cases
     * @return  string
     */
    public static function transliterate_to_ascii($str, $case = 0)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _transliterate_to_ascii($str, $case);
    }

    /**
     * Returns the length of the given string. This is a UTF8-aware version
     * of [strlen](http://php.net/strlen).
     *     $length = static::strlen($str);
     * @param   string $str    string being measured for length
     * @return  integer
     * @uses    static::$server_utf8
     */
    public static function strlen($str)
    {
        if(static::$server_utf8)
        {
            return mb_strlen($str, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strlen($str);
    }

    /**
     * Finds position of first occurrence of a UTF-8 string. This is a
     * UTF8-aware version of [strpos](http://php.net/strpos).
     *     $position = static::strpos($str, $search);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    haystack
     * @param   string $search needle
     * @param   integer $offset offset from which character in haystack to start searching
     * @return  integer position of needle
     * @return  boolean FALSE if the needle is not found
     * @uses    static::$server_utf8
     */
    public static function strpos($str, $search, $offset = 0)
    {
        if(static::$server_utf8)
        {
            return mb_strpos($str, $search, $offset, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strpos($str, $search, $offset);
    }

    /**
     * Finds position of last occurrence of a char in a UTF-8 string. This is
     * a UTF8-aware version of [strrpos](http://php.net/strrpos).
     *     $position = static::strrpos($str, $search);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    haystack
     * @param   string $search needle
     * @param   integer $offset offset from which character in haystack to start searching
     * @return  integer position of needle
     * @return  boolean FALSE if the needle is not found
     * @uses    static::$server_utf8
     */
    public static function strrpos($str, $search, $offset = 0)
    {
        if(static::$server_utf8)
        {
            return mb_strrpos($str, $search, $offset, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strrpos($str, $search, $offset);
    }

    /**
     * Returns part of a UTF-8 string. This is a UTF8-aware version
     * of [substr](http://php.net/substr).
     *     $sub = static::substr($str, $offset);
     * @author  Chris Smith <chris@jalakai.co.uk>
     * @param   string $str    input string
     * @param   integer $offset offset
     * @param   integer $length length limit
     * @return  string
     * @uses    static::$server_utf8
     * @uses    static::$charset
     */
    public static function substr($str, $offset, $length = null)
    {
        if(static::$server_utf8)
        {
            return ($length === null)
            ? mb_substr($str, $offset, mb_strlen($str), static::$charset)
            : mb_substr($str, $offset, $length, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _substr($str, $offset, $length);
    }

    /**
     * Replaces text within a portion of a UTF-8 string. This is a UTF8-aware
     * version of [substr_replace](http://php.net/substr_replace).
     *     $str = static::substr_replace($str, $replacement, $offset);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str            input string
     * @param   string $replacement    replacement string
     * @param   integer $offset         offset
     * @return  string
     */
    public static function substr_replace($str, $replacement, $offset, $length = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _substr_replace($str, $replacement, $offset, $length);
    }

    /**
     * Makes a UTF-8 string lowercase. This is a UTF8-aware version
     * of [strtolower](http://php.net/strtolower).
     *     $str = static::strtolower($str);
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str    mixed case string
     * @return  string
     * @uses    static::$server_utf8
     */
    public static function strtolower($str)
    {
        if(static::$server_utf8)
        {
            return mb_strtolower($str, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strtolower($str);
    }

    /**
     * Makes a UTF-8 string uppercase. This is a UTF8-aware version
     * of [strtoupper](http://php.net/strtoupper).
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str    mixed case string
     * @return  string
     * @uses    static::$server_utf8
     * @uses    static::$charset
     */
    public static function strtoupper($str)
    {
        if(static::$server_utf8)
        {
            return mb_strtoupper($str, static::$charset);
        }

        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strtoupper($str);
    }

    /**
     * Makes a UTF-8 string's first character uppercase. This is a UTF8-aware
     * version of [ucfirst](http://php.net/ucfirst).
     *     $str = static::ucfirst($str);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    mixed case string
     * @return  string
     */
    public static function ucfirst($str)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _ucfirst($str);
    }

    /**
     * Makes the first character of every word in a UTF-8 string uppercase.
     * This is a UTF8-aware version of [ucwords](http://php.net/ucwords).
     *     $str = static::ucwords($str);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    mixed case string
     * @return  string
     * @uses    static::$server_utf8
     */
    public static function ucwords($str)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _ucwords($str);
    }

    /**
     * Case-insensitive UTF-8 string comparison. This is a UTF8-aware version
     * of [strcasecmp](http://php.net/strcasecmp).
     *     $compare = static::strcasecmp($str1, $str2);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str1   string to compare
     * @param   string $str2   string to compare
     * @return  integer less than 0 if str1 is less than str2
     * @return  integer greater than 0 if str1 is greater than str2
     * @return  integer 0 if they are equal
     */
    public static function strcasecmp($str1, $str2)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strcasecmp($str1, $str2);
    }

    /**
     * Returns a string or an array with all occurrences of search in subject
     * (ignoring case) and replaced with the given replace value. This is a
     * UTF8-aware version of [str_ireplace](http://php.net/str_ireplace).
     * [!!] This function is very slow compared to the native version. Avoid
     * using it when possible.
     * @author  Harry Fuecks <hfuecks@gmail.com
     * @param   string|array $search     text to replace
     * @param   string|array $replace    replacement text
     * @param   string|array $str        subject text
     * @param   integer $count      number of matched and replaced needles will be returned via this parameter which is passed by reference
     * @return  string  if the input was a string
     * @return  array   if the input was an array
     */
    public static function str_ireplace($search, $replace, $str, & $count = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _str_ireplace($search, $replace, $str, $count);
    }

    /**
     * Case-insenstive UTF-8 version of strstr. Returns all of input string
     * from the first occurrence of needle to the end. This is a UTF8-aware
     * version of [stristr](http://php.net/stristr).
     *     $found = static::stristr($str, $search);
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    input string
     * @param   string $search needle
     * @return  string  matched substring if found
     * @return  FALSE   if the substring was not found
     */
    public static function stristr($str, $search)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _stristr($str, $search);
    }

    /**
     * Finds the length of the initial segment matching mask. This is a
     * UTF8-aware version of [strspn](http://php.net/strspn).
     *     $found = static::strspn($str, $mask);
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    input string
     * @param   string $mask   mask for search
     * @param   integer $offset start position of the string to examine
     * @param   integer $length length of the string to examine
     * @return  integer length of the initial segment that contains characters in the mask
     */
    public static function strspn($str, $mask, $offset = null, $length = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strspn($str, $mask, $offset, $length);
    }

    /**
     * Finds the length of the initial segment not matching mask. This is a
     * UTF8-aware version of [strcspn](http://php.net/strcspn).
     *     $found = static::strcspn($str, $mask);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    input string
     * @param   string $mask   mask for search
     * @param   integer $offset start position of the string to examine
     * @param   integer $length length of the string to examine
     * @return  integer length of the initial segment that contains characters not in the mask
     */
    public static function strcspn($str, $mask, $offset = null, $length = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strcspn($str, $mask, $offset, $length);
    }

    /**
     * Pads a UTF-8 string to a certain length with another string. This is a
     * UTF8-aware version of [str_pad](http://php.net/str_pad).
     *     $str = static::str_pad($str, $length);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str                input string
     * @param   integer $final_str_length   desired string length after padding
     * @param   string $pad_str            string to use as padding
     * @param   string $pad_type           padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
     * @return  string
     */
    public static function str_pad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _str_pad($str, $final_str_length, $pad_str, $pad_type);
    }

    /**
     * Converts a UTF-8 string to an array. This is a UTF8-aware version of
     * [str_split](http://php.net/str_split).
     *     $array = static::str_split($str);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str            input string
     * @param   integer $split_length   maximum length of each chunk
     * @return  array
     */
    public static function str_split($str, $split_length = 1)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _str_split($str, $split_length);
    }

    /**
     * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
     *     $str = static::strrev($str);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    string to be reversed
     * @return  string
     */
    public static function strrev($str)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _strrev($str);
    }

    /**
     * Strips whitespace (or other UTF-8 characters) from the beginning and
     * end of a string. This is a UTF8-aware version of [trim](http://php.net/trim).
     *     $str = static::trim($str);
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str        input string
     * @param   string $charlist   string of characters to remove
     * @return  string
     */
    public static function trim($str, $charlist = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _trim($str, $charlist);
    }

    /**
     * Strips whitespace (or other UTF-8 characters) from the beginning of
     * a string. This is a UTF8-aware version of [ltrim](http://php.net/ltrim).
     *     $str = static::ltrim($str);
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str        input string
     * @param   string $charlist   string of characters to remove
     * @return  string
     */
    public static function ltrim($str, $charlist = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _ltrim($str, $charlist);
    }

    /**
     * Strips whitespace (or other UTF-8 characters) from the end of a string.
     * This is a UTF8-aware version of [rtrim](http://php.net/rtrim).
     *     $str = static::rtrim($str);
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string $str        input string
     * @param   string $charlist   string of characters to remove
     * @return  string
     */
    public static function rtrim($str, $charlist = null)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _rtrim($str, $charlist);
    }

    /**
     * Returns the unicode ordinal for a character. This is a UTF8-aware
     * version of [ord](http://php.net/ord).
     *     $digit = static::ord($character);
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string $chr    UTF-8 encoded character
     * @return  integer
     */
    public static function ord($chr)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _ord($chr);
    }

    /**
     * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
     * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
     * Occurrences of the BOM are ignored. Surrogates are not allowed.
     *     $array = static::to_unicode($str);
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
     * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
     * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
     * @param   string $str    UTF-8 encoded string
     * @return  array   unicode code points
     * @return  FALSE   if the string is invalid
     */
    public static function to_unicode($str)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _to_unicode($str);
    }

    /**
     * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
     * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
     * Occurrances of the BOM are ignored. Surrogates are not allowed.
     *     $str = static::to_unicode($array);
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
     * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
     * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
     * @param   array $str    unicode code points representing a string
     * @return  string  utf8 string of characters
     * @return  boolean FALSE if a code point cannot be found
     */
    public static function from_unicode($arr)
    {
        if(!isset(static::$called[__FUNCTION__]))
        {
            require 'utf8' . DIRECTORY_SEPARATOR . __FUNCTION__;

            // Function has been called
            static::$called[__FUNCTION__] = TRUE;
        }

        return _from_unicode($arr);
    }

} // End UTF8

if(UTF8::$server_utf8 === null)
{
    // Determine if this server supports UTF-8 natively
    UTF8::$server_utf8 = extension_loaded('mbstring');
}
