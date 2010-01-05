<?php
 
/**

 * This file contains the main include file for the FileLink_ParserFunction extension of 
 * MediaWiki. This code is released under the GNU General Public License.
 *
 *  
 * @author Matheus Garcia <garcia.figueiredo@gmail.com>
 * @copyright Copyleft 2010, Matheus Garcia
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package MediaWikiExtensions
 * @version 0.1
 */
 
/**
 * Register the extension with MediaWiki
 */ 
$wgExtensionFunctions[] = 'registerFileLinkPF';
$wgHooks['LanguageGetMagic'][]       = 'registerFileLinkPF_Magic';
 
/**
 * Sets the tag that this extension looks for and the function by which it
 * operates
 */
function registerFileLinkPF()
{
    global $wgParser;
    $wgParser->setFunctionHook('file', 'renderFileLinkPF' );
}
 
 
function registerFileLinkPF_Magic( &$magicWords, $langCode ) {
        # Add the magic word
        # The first array element is case sensitive, in this case it is not case sensitive
        # All remaining elements are synonyms for our parser function
        $magicWords['file'] = array( 0, 'file' );
        # unless we return true, other parser functions extensions won't get loaded.
        return true;
}


/**
 * Renders a file protocol link based on the information provided by $input.
 *
 * @param string
 *  The string should be in the following format:
 *      URI[;link text]
 *  One example for a Windows environment would be:
 *      c:/something.txt|some nice text
 * @return string
 *  Returns an anchor tag for the given input. For the example above
 *  the return string would be
 *      <a style="color:green" href="file:///c:/something.txt>some nice text</a>
 * The links are rendered in green text color to make it easier to recognize 
 * them as local shares.
 */

function renderFileLinkPF(&$parser, $input, $param2 = '')
{
    global $wgParser;

    $exploded = explode('|', $input);
    $uri = htmlentities($exploded[0], ENT_COMPAT, "UTF-8");
 
    if (!isset($exploded[1]) || empty($exploded[1])) {
        // no linktext has been specified ==> use the URI as linktext
        $linktext = $uri;
    }
    else {
        $linktext = htmlentities($exploded[1], ENT_COMPAT, "UTF-8");
    }


   $linkResult = sprintf('<a style="color:green" href="file:///%s">%s</a>', 
        $uri, $linktext);

   return array($linkResult, 'isHTML' => true, 'noparse' => true);
}
 
#credits for [[Special:Version]]
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'FileLink_ParserFunction',
        'author' => 'Matheus Garcia',
        'description' => 'Enable links using the file protocol',
        'url' => 'http://www.mediawiki.org/wiki/Extension:FileLink_ParserFunction');	
?>

