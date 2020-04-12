<?php
class Commom
{
    /**
     *
     * @param string $url
     * @param string $name
     * @return string
     */
    public static function getQueryStringValue($url, $name)
    {
        $pattern  = "/$name=([^>]*)/";
        preg_match($pattern, $url, $matches);
        if(count($matches) > 1)
            return $matches[1];
        else
            return "";
    }
    public static function replaceQueryString($url, $name, $value)
    {
        $pattern  = $name."=[^>]*";
        $replacement = $name.'='.$value;
        $result = ereg_replace($pattern , $replacement, $url);

        if(empty($result))
            return $replacement;
        else
            return $result;
    }

    public static function paging($itemsPerPg, $currentPg, $totalItems, $inputId='pg', $submit=false)
    {
        $html = "";
        $totalPag = ceil($totalItems / $itemsPerPg);

        $php_self = $_SERVER['PHP_SELF'];
        $rqst_uri = $_SERVER['REQUEST_URI'];

        $params = strstr($rqst_uri, '?');

        if (empty($params))
        {
            $rqst_uri .= "?".$inputId."=1";
        } else
        {
            if(stristr($params, $inputId.'=') === FALSE)
            {
                $rqst_uri .= "&".$inputId."=1";
            }
        }

        if($totalItems > $itemsPerPg)
        {

            $html .= "<div class=\"paging\">";

            if($currentPg >= 2)
            {
                $html .= "<a href=\"".($submit?"javascript:submitPage(1)":self::replaceQueryString($rqst_uri, $inputId, 1))."\">&laquo; primeira</a>&nbsp;&nbsp;";
            }
            if($currentPg-1 >= 2)
            {
                $html .= "<a href=\"".($submit?"javascript:submitPage(".($currentPg-1).")":self::replaceQueryString($rqst_uri, $inputId, $currentPg-1))."\">&lsaquo; anterior</a>&nbsp;&nbsp;";
            }

            $len = strlen((string) $currentPg);
            $r = (floor($currentPg/10))*10;

            if($r == $currentPg)
                $d = $currentPg-9;
            else
            {
                $d = $r+1;
            }

            if($d+9 >= $totalPag) $m = $totalPag;
            else $m = $d+9;

            if($d >= 11) $html .= "... ";

            for($j=$d; $j<=$m; $j++)
            {
                if($j != $currentPg)
                {
                    $html .= "<a href=\"".($submit?"javascript:submitPage(".$j.")":self::replaceQueryString($rqst_uri, $inputId, $j))."\">".$j."</a>\n";
                } else
                {
                    $html .= "<span class=\"currentPage\">".$j."</span>\n";
                }
                if($j == $m)
                {
                    if ($j < $totalPag)
                    {
                        $html .= " ... <a href=\"".($submit?"javascript:submitPage(".$totalPag.")":self::replaceQueryString($rqst_uri, $inputId, $totalPag))."\">".$totalPag."</a>\n";
                    }
                }
            }
            //if($m < $totalPag) $html .= " ...";

            $html .= "&nbsp;&nbsp;";

            if(($currentPg >= 1 && $currentPg < $totalPag-1) && $totalPag > 2)
            {
                $html .= "<a href=\"".($submit?"javascript:submitPage(".($currentPg+1).")":self::replaceQueryString($rqst_uri, $inputId, $currentPg+1))."\">pr&oacute;xima &rsaquo;</a>&nbsp;&nbsp;\n";
            }
            if($currentPg < $totalPag)
            {
                $html .= "<a href=\"".($submit?"javascript:submitPage(".$totalPag.")":self::replaceQueryString($rqst_uri, $inputId, $totalPag))."\">&uacute;ltima &raquo;</a>\n";
            }

            $html .= "&nbsp;&nbsp;";

            $html .= "<span class=\"goto\">Ir para: <select name=\"pages\" onchange=\"selectPage(this)\">\n";
            for($w=1; $w<=$totalPag; $w++)
            {
                $html .= "<option value=\"".$w."\"";
                if($w == $currentPg)
                {
                    $html .= " selected=\"selected\"";
                }
                $html .= ">".$w."</option>\n";
            }
            $html .= "</select></span>";

            $html .= "</div>\n";
            if($submit)
            {
                $html .= "<script language=\"javascript\" type=\"text/javascript\">\n";
                $html .= "function submitPage(value)\n";
                $html .= "{\n";
                $html .= "	jQuery('#".$inputId."').val(value);\n";
                $html .= "	document.forms[0].submit();\n";
                $html .= "}\n";
                $html .= "function selectPage(o)\n";
                $html .= "{\n";
                $html .= "	var page = jQuery('option:selected', o).attr('value');\n";
                $html .= "	jQuery('#".$inputId."').val(page);\n";
                $html .= "	document.forms[0].submit();\n";
                $html .= "}\n";
                $html .= "</script>\n";
            }
        }
        return $html;
    }
}
?>