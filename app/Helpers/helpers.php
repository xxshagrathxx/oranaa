<?php
    // To get the current domain of a given url
    function getDomainName($url)
    {
        $parse = parse_url($url);
        return $parse['host'];
    }
?>