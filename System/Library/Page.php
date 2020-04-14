<?php

/**
 * File: Page.php.
 * Author: Self
 * Standard: PSR-2. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 1/21/2020
 */

namespace System\Library;

final class Page
{
    private $title;
    private $description;
    private $keywords;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }
}

