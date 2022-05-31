<?php
class Replacer{
    function replaceContent($content)
    {
        $word6Regex = "/(?!<|\/|\")(\b[A-Za-z]{6}\b)(?!>|\/|\"|:|\.|-|=|;)/";
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);
        $replacement = '$0™';
        self::domTextReplace($word6Regex, $replacement, $doc, true);
        return utf8_encode($doc->saveHTML());
    }
    // I copied here the function for everyone to find it quick
    function domTextReplace($search, $replace, DOMNode &$domNode, $isRegEx = false)
    {
        if ($domNode->hasChildNodes()) {
            $children = array();
            // since looping through a DOM being modified is a bad idea we prepare an array:
            foreach ($domNode->childNodes as $child) {
                $children[] = $child;
            }
            foreach ($children as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $newText = preg_replace($search, $replace, $child->wholeText);
                    $newTextNode = $domNode->ownerDocument->createTextNode($newText);
                    $domNode->replaceChild($newTextNode, $child);
                } else {
                    self::domTextReplace($search, $replace, $child, $isRegEx);
                }
            }
        }
    }
}

?>