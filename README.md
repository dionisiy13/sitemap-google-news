# Google News Sitemap

[![Packagist](https://img.shields.io/packagist/v/dionisiy13/google-news-sitemap.svg)](https://packagist.org/packages/dionisiy13/google-news-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/dionisiy13/google-news-sitemap.svg?style=flat-square)](https://packagist.org/packages/dionisiy13/google-news-sitemap)

Class for generating the sitemap for google news. More about google news sitemap [here](https://support.google.com/news/publisher/answer/74288?hl=uk)

Example of the sitemap from google 
```
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
       xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
 <url>
   <loc>http://www.example.org/business/article55.html</loc>
   <news:news>
     <news:publication>
       <news:name>The Example Times</news:name>
       <news:language>en</news:language>
     </news:publication>
     <news:genres>PressRelease, Blog</news:genres>
     <news:publication_date>2008-12-23</news:publication_date>
     <news:title>Companies A, B in Merger Talks</news:title>
     <news:keywords>business, merger, acquisition, A, B</news:keywords>
     <news:stock_tickers>NASDAQ:A, NASDAQ:B</news:stock_tickers>
   </news:news>
   <image:image>
      <image:loc>https://example.com/image.jpg</image:loc>
    </image:image>
    <image:image>
      <image:loc>https://example.com/photo.jpg</image:loc>
    </image:image>
 </url>
</urlset>
   ```

## Install

Via Composer

``` bash
$ composer require dionisiy13/google-news-sitemap
```

## Usage

``` php
$sitemap = new \Dionisiy\SitemapGoogle\Sitemap($pathToFile);
$siteName = "example.com";
foreach ($posts as $item) {
    $sitemap->setGenres("Blog");
    $sitemap->setKeywords($item['tags']);
    $sitemap->setLanguage("en");
    $sitemap->setLoc($item['url']);
    $sitemap->setName($siteName);
    $sitemap->setPublicationDate($item['publishAt']->getTimestamp());
    $sitemap->setTitle($item['title']);
    $sitemap->setImages($item['images']);
    $sitemap->addItem();
}
$sitemap->write();
```

```$posts``` - list of news 

```$pathToFile``` - where the file should apear. For example ```getcwd() . '/public' . '/sitemap_google_news.xml'```

```$item['date']``` - should be DateTime

Tags and genres could be empty.

## Credits

- [Dan Kurasov](https://github.com/dionisiy13)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
