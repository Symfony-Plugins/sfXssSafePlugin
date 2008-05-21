<?php

/**
 * XssSafe Helper - Clean cross site scripting exploits from string 
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Alexandre Mogère <amogere@sqli.com>
 *
 * @uses <a href="http://htmlpurifier.org/">HTML Purifier</a>
 */

require_once(sfConfig::get('sf_plugins_dir').'/sfXssSafePlugin/lib/vendor/htmlpurifier/HTMLPurifier.auto.php');

/**
 * The function runs HTML Purifier as an alternative between
 * escaping raw and escaping entities.
 *
 * @param string $dirty_html the value to clean
 * @return string the escaped value
 */
function esc_xsssafe($dirty_html)
{
  if (!$dirty_html)
  {
    return '';
  }
  
  set_error_handler('XssSafeErrorHandler');
  
  static $purifier = false;
  
  if (!$purifier)
  {
    // Set configuration
    $config = HTMLPurifier_Config::createDefault();

    $definitions = sfConfig::get('app_sfXssSafePlugin_definition');
    if (!empty($definitions))
    {
      foreach ($definitions as $def => $conf)
      {
        if (!empty($conf))
        {
          foreach ($conf as $directive => $values)
          {
            $config->set($def, $directive, $values); // $values can be a string or an ArrayList
          }
        }
      }
    }

    // Set the cache directory into Symfony cache directory
    $config->set('Cache', 'SerializerPath', sfConfig::get('sf_cache_dir'));
  
    $purifier = new HTMLPurifier($config);
  }
  
  $clean_html = $purifier->purify($dirty_html);
  
  restore_error_handler();

  return $clean_html;
}

define('ESC_XSSSAFE', 'esc_xsssafe');

/**
 * Error handler.
 *
 * @param mixed Error number
 * @param string Error message
 * @param string Error file
 * @param mixed Error line
 */
function XssSafeErrorHandler($errno, $errstr, $errfile, $errline)
{
  if (($errno & error_reporting()) == 0)
  {
    return;
  }

  throw new sfException(sprintf('{XssSafeHelper} Error at %s line %s (%s)', $errfile, $errline, $errstr));
}

?>