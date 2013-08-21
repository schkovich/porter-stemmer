<?php
/**
 * Handles Remote Procedure Call
 *
 * $LastChangedDate: 2010-10-29 03:51:47 +0200 (Fri, 29 Oct 2010) $
 * $Rev$
 *
 * @version    SVN: $Id: index.php 6 2010-10-29 01:51:47Z schkovich $
 *
 * PHP version 5.3
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category PorterStemmer
 * @uses PorterStemmer\PorterStemmer
 * @author Goran Miskovic <schkovich at gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright © 2010 Goran Miskovic
 */

namespace PorterStemmer;

/**
 * Set errors to be displayed and error reporting level to E_ALL | E_STRICT.
 * @link http://pear.php.net/manual/en/standards.e_strict.php
 */
ini_set("display_errors", 1);
error_reporting(E_ALL | E_STRICT);

// {{{ constants

/**
 * Holds path to source files
 */
define('BASE_PATH', dirname(__FILE__) . \DIRECTORY_SEPARATOR . '..'
        . \DIRECTORY_SEPARATOR . 'Libs' . \DIRECTORY_SEPARATOR);
/**
 * Holds path to ini storage files
 */
define("CONFIG_PATH", \BASE_PATH . '..' . \DIRECTORY_SEPARATOR
        . 'Config' . \DIRECTORY_SEPARATOR);
// }}}

/**
 * Define autoloader
 */
\spl_autoload_register(function ($className) {
            require \BASE_PATH .
                    str_replace("\\", \DIRECTORY_SEPARATOR, $className) . ".php";
        });

function parseIniFile($fileName)
{
    $returnValue = array();

    if (false === file_exists(\CONFIG_PATH . $fileName)) :
        throw new \RuntimeException("File $fileName is not accessible!", 2000);
    endif;

    $returnValue = parse_ini_file(\CONFIG_PATH . $fileName, true);

    /*
     * From PHP 5.2.7 on syntax error this function
     * will return FALSE rather then an empty array.
     * @link http://us2.php.net/manual/en/function.parse-ini-file.php
     */
    if (empty($returnValue) || (FALSE === $returnValue)) :
        throw new \Exception("Failed parsing file $fileName", 2001);
    endif;

    return (array) $returnValue;
}
$stemmer = new PorterStemmer2();
$dictionary = parseIniFile("sample.ini");
//$dictionary = array("crowquil" => "crowquil");
$img = '';
$alt = '';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>The English (Porter2) Stemming Algorithm Test Page</title>
    </head>
    <body>
        <table style="width: 314px; text-align: left">
            <thead>
                <tr>
                    <th style="width: 45%">Word</th>
                    <th style="width: 45%">Stem</th>
                    <th style="width: 10%">Result</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dictionary as $key => $value) :
                    $stem = $stemmer->stem($key);
                    if ($stem === $value) :

                        $img = "accept.png";
                        $alt = "Correct";
                        else:
                        $img = "delete.png";
                        $alt = "Wrong";
                    endif;
                ?>
                    <tr>
                        <td><?php echo $key ?></td>
                        <td><?php echo $stem ?></td>
                        <td>
                            <img src="images/<?php echo $img ?>"
                                 alt="<?php echo $alt ?>" />
                        </td>
                    </tr>
                <?php
                    endforeach;
                ?>
            </tbody>
        </table>
    </body>
</html>
