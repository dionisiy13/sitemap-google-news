<?php

namespace Dionisiy\SitemapGoogle;

use XMLWriter;

class Sitemap
{
    private $filePath;
    /**
     * @var bool if XML should be indented
     */
    private $useIndent = true;

    /**
     * @var XMLWriter
     */
    private $writer;
    private $location;
    private $title;
    private $name;
    private $date;
    private $keywords;
    private $genres;
    private $lang;

    /**
     * @param string $filePath path of the file to write to
     * @param bool $useXhtml is XHTML namespace should be specified
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($filePath, $useXhtml = false)
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(
                "Please specify valid file path. Directory not exists. You have specified: {$dir}."
            );
        }
        $this->filePath = $filePath;
        $this->useXhtml = $useXhtml;
        $this->createNewFile();
    }

    public function setKeywords(array $keywords)
    {
        $this->keywords = $keywords;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setPublicationDate($date)
    {
        $this->date = $date;
    }

    public function setGenres(string $genres)
    {
        $this->genres = $genres;
    }

    public function setLanguage(string $lang)
    {
        $this->lang = $lang;
    }

    public function setName(string $siteName)
    {
        $this->name = $siteName;
    }

    public function setLoc(string $location)
    {
        $this->location = $location;
    }

    /**
     * Creates new file
     * @throws \RuntimeException if file is not writeable
     */
    private function createNewFile()
    {
        $filePath = $this->filePath;
        if (file_exists($filePath)) {
            $filePath = realpath($filePath);
            if (is_writable($filePath)) {
                unlink($filePath);
            } else {
                throw new \RuntimeException("File \"$filePath\" is not writable.");
            }
        }
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->setIndent($this->useIndent);
        $this->writer->startElement('urlset');
        $this->writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->writer->writeAttribute('xmlns:news', 'http://www.google.com/schemas/sitemap-news/0.9');

    }

    /**
     * Writes closing tags to current file
     */
    private function finishFile()
    {
        if ($this->writer !== null) {
            $this->writer->endElement();
            $this->writer->endDocument();
            $this->flush(true);
        }
    }

    /**
     * Finishes writing
     */
    public function write()
    {
        $this->finishFile();
    }
    /**
     * Flushes buffer into file
     * @param bool $finishFile Pass true to close the file to write to, used only when useGzip is true
     */
    private function flush($finishFile = false)
    {
        file_put_contents($this->filePath, $this->writer->flush(true), FILE_APPEND);
    }

    /**
     * Takes a string and validates, if the string
     * is a valid url
     *
     * @param string $location
     * @throws \InvalidArgumentException
     */
    protected function validateLocation($location) {
        if (false === filter_var($location, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(
                "The location must be a valid URL. You have specified: {$location}."
            );
        }
    }

    /**
     * Adds a new item to the sitemap
     *
     * @throws \InvalidArgumentException
     */
    public function addItem()
    {
        $this->validateLocation($this->location);

        $this->writer->startElement('url');
            $this->writer->writeElement('loc', $this->location);
            $this->writer->startElement('news:news');
                $this->writer->startElement('news:publication');
                    $this->writer->writeElement('news:name', $this->name);
                    $this->writer->writeElement('news:language', $this->lang);
                $this->writer->endElement();
                if (!empty($this->genres)) {
                    $this->writer->writeElement('news:genres', $this->genres);
                }
                $this->writer->writeElement('news:publication_date', date('c', $this->date));
                $this->writer->writeElement('news:title', $this->title);
                if (!empty($this->keywords)) {
                    $this->writer->writeElement('news:keywords', implode(", ",$this->keywords));
                }
            $this->writer->endElement();
        $this->writer->endElement();
    }
}
