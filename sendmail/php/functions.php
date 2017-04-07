<?php

function stripslashes_deep(&$value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    return $value;
}

// oldschool setups:
if (get_magic_quotes_gpc()) {
    stripslashes_deep($_GET);
    stripslashes_deep($_POST);
    stripslashes_deep($_REQUEST);
}

// simple string highlight for search results.
function _shl($string, $highlight) {
    $highlight = trim($highlight);
    if (!$highlight)
        return $string;
    return preg_replace('/' . preg_quote($highlight, '/') . '/i', '<span style="background-color:#FFFF66">$0</span>', $string);
}

if (isset($_REQUEST['jsonp']) && $_REQUEST['jsonp']) {
    echo '<script language="javascript">' . $_REQUEST['jsonp'] . '</script>';
    exit;
}

function _l($text) {
    // read in from the global label array
    global $labels;
    $argv = func_get_args();
    // see if the first one is a lang label
    if (isset($labels[$text])) {
        $argv[0] = $labels[$text];
    }
    // use this for building up the language array.
    // visit index.php?dump_lang=true to get a csv file of language vars.
    if (_DEMO_MODE) {
        //$_SESSION['l'][$text] = true;
    }
    return call_user_func_array('sprintf', $argv);
}

function get_languages() {
    $files = @glob("php/lang/*.php");
    if (!is_array($files))
        $files = array();
    $languages = array();
    foreach ($files as $file) {
        $languages[] = basename(str_replace('.php', '', $file));
    }
    return $languages;
}

function input_date($date, $include_time = false) {

    if (
            !$date ||
            (preg_match('/[a-z]/i', $date) && !preg_match('/^[\+-]\d/', $date)) ||
            preg_match('/^\d+$/', $date)
    )
        return '';

    // takes a user input date and returns the mysql YYYY-MM-DD valid format.
    // 1 = DD/MM/YYYY
    // 2 = YYYY/MM/DD
    // 3 = MM/DD/YYYY
    // could use sscanf below, but still wanted to run preg_match
    // so used implode(explode( instead... meh

    switch (_DATE_INPUT) {
        case 1:
            if (preg_match('#^\d?\d([-/])\d?\d\1\d{2,4}$#', $date, $matches)) {
                $date = implode("-", array_reverse(explode($matches[1], $date)));
                if (strtotime($date)) {
                    $date = date('Y-m-d' . (($include_time) ? ' H:i:s' : ''), strtotime($date));
                    break;
                }
            }
        case 2:
            if (preg_match('#^\d{2,4}([-/])\d?\d\1\d?\d$#', $date, $matches)) {
                $date = implode("-", explode($matches[1], $date));
                if (strtotime($date)) {
                    $date = date('Y-m-d' . (($include_time) ? ' H:i:s' : ''), strtotime($date));
                    break;
                }
            }
        case 3:
            if (preg_match('#^\d?\d([-/])\d?\d\1\d{2,4}$#', $date, $matches)) {
                $date_bits = explode($matches[1], $date);
                $date = $date_bits[2] . '-' . $date_bits[0] . '-' . $date_bits[1];
                if (strtotime($date)) {
                    $date = date('Y-m-d' . (($include_time) ? ' H:i:s' : ''), strtotime($date));
                    break;
                }
            }
        default:
            $date = date('Y-m-d' . (($include_time) ? ' H:i:s' : ''), strtotime($date));
    }

    return $date;
}

function print_date($date, $include_time = false, $input_format = false) {
    if (!$date || (preg_match('/[a-z]/i', $date) && !preg_match('/^[\+-]\d/', $date)))
        return '';
    if (strpos($date, '0000-00-00') !== false)
        return '';
    if (is_numeric($date)) {
        // we have a timestamp, simply spit this out
        $time = $date;
    } else {
        $time = strtotime(input_date($date, $include_time));
    }
    if ($input_format) {
        switch (_DATE_INPUT) {
            case 1:
                $date = date("d/m/Y", $time);
                break;
            case 2:
                $date = date("Y/m/d", $time);
                break;
            case 3:
                $date = date("m/d/Y", $time);
                break;
            default:
                $date = date("Y-m-d", $time);
                break;
        }
    } else {
        $date = date(_DATE_FORMAT, $time);
    }
    if ($include_time) {
        $date.= ' ' . date("H:i:s", $time);
    }
    return $date;
}

if (!function_exists("str_getcsv")) {

    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\') {
        $bs = '\\';
        $enc = $bs . $enclosure;
        $esc = $bs . $escape;
        $delim = $bs . $delimiter;
        $encesc = ($enc == $esc) ? $enc : $enc . $esc;
        $pattern = "/($enc(?:[^$encesc]|$esc$enc)*$enc|[^$enc$delim]*)$delim/";
        preg_match_all($pattern, $input . $delimiter, $matches);
        $parts = array();
        foreach ($matches[1] as $part) {
            $len = strlen($part);
            if ($len >= 2 && $part{0} == $enclosure) {
                $part = substr($part, 1, $len - 2);
                $part = str_replace($escape . $enclosure, $enclosure, $part);
            } $parts[] = $part;
        } return $parts;
    }

}

if (!function_exists("xmlentities")) {

    function xmlentities($string) {
        return str_replace(array('&', '"', "'", '<', '>', '�'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;', '&apos;'), $string);
    }

}

if (!function_exists("curPageURL")) {

    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}

if (!function_exists('cut_str')) {
    function cut_str($str = '', $length = '', $chr = '[...]') {
        if ($length == '') {
            $length = 30;
        }
        if (strlen($str) < $length) {
            return $str;
        } else {
            return substr($str, 0, $length) . $chr;
        }
    }
}

    function connect() {
        $cnn = mysql_connect(_DB_SERVER_WEB, _DB_USER_WEB, _DB_PASS_WEB);
        mysql_select_db(_DB_NAME_WEB, $cnn);
        return $cnn;

    }

    function close() {
        $cnn = connect();
        mysql_close($cnn);
    }
    
    function selectQuery($sql) {
        $result = mysql_query($sql, connect());
        $data = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($result)) {
            $data[$i++] = $row;
        }
        close();
        return $data;
    }

    function excuteQuery($sql) {
        $result = mysql_query($sql, connect());
        if (!$result) {
            echo '<script type="text/javascript">alert("Could not run query: ' . mysql_error() . '");</script>';
            exit;
        } else {
            return true;
        }
        close();
    }
    
    function getArticleByCodeCategory($code_category)
    {
        $sql = "select a.article_id, a.category_id, a.image, ad.title, ad.description, ad.long_description from article a, article_description ad 
                where ad.lang_code = 'fr' and a.article_id = ad.article_id and a.`status` = 1 
                and a.category_id = (select category_id from category where category.category_code = '$code_category')
                ORDER BY sort_order"; 

        return selectQuery($sql);
    }
    
    function getArticleByCodeParent()
    {
        $sql = "select a.article_id, a.category_id, a.image, ad.title, ad.description, ad.long_description from article a, article_description ad 
                where ad.lang_code = 'fr' and a.article_id = ad.article_id and a.`status` = 1 
                and a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 17)
                ORDER BY sort_order"; 

        return selectQuery($sql);
    }
    
    function getArticleByIdDefaultLang($art_id)
    {
        //$lang = $l;

        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, cd.name, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = 'fr' AND a.article_id = '$art_id'";
		
        return selectQuery($sql);
    }
    
