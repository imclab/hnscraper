<?php
class Parser
{
    private $_links = Array();
    private $_dom;

    public function __construct($dom)
    {
        $this->_dom = $dom;
    }

    public function extractLinks()
    {
        $tags = $this->_dom->getElementsByTagName('a');

        foreach ($tags as $tag) {
            // Look for External links
            if (preg_match('/^vote\?for=([0-9]*)/', $tag->getAttribute('href'), $matches)) {
                // Extract ID from votelink
                $id = $matches[1];
                // Find table cell containing link
                $td = $tag->parentNode->parentNode->nextSibling;
                // Extract Link
                $link = $td->getElementsByTagName('a')->item(0);
                // Extract Url and Title
                $this->_links[$id]['href']  = $link->getAttribute('href');
                $this->_links[$id]['title'] = $link->nodeValue;
                // Get site
                $siteString = $td->getElementsByTagName('span')->item(0)->nodeValue;
                preg_match('/^ \((.*)\) $/', $siteString, $siteMatches);
                $this->_links[$id]['site'] = $siteMatches[1];
                // Get Score
                $scoreSpan   = $this->_dom->getElementById("score_{$id}");
                $scoreString = $scoreSpan->nodeValue;
                preg_match('/^([0-9]*) points$/', $scoreString, $scoreMatches);
                $this->_links[$id]['points'] = $scoreMatches[1];
                // Get User
                $this->_links[$id]['user'] = $scoreSpan->parentNode->getElementsByTagName('a')->item(0)->nodeValue;
                // Get Comments
                $commentString = $scoreSpan->parentNode->getElementsByTagName('a')->item(1)->nodeValue;
                preg_match('/^([0-9]*) comments$/', $commentString, $commentMatches);
                $this->_links[$id]['comments'] = isset($commentMatches[1]) ? $commentMatches[1] : 0;
            }
        }
    }

    public function getLinks()
    {
        return $this->_links;
    }

}
