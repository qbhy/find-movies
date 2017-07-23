<?php

namespace Qbhy\FindMovies;

use GuzzleHttp\Client;

class Finder
{

    /**
     * @var Client
     */
    static $http;

    static $infoMap = [
        '地区' => 'region',
        '上映日期' => 'releasedAt',
        '更新日期' => 'updatedAt',
        '片长' => 'timeLength',
        '语言' => 'language',
        '类型' => 'type',
        '导演' => 'director',
        '豆瓣评分' => 'score'
    ];

    public static function init()
    {
        static::$http = new \GuzzleHttp\Client([
            'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36']
        ]);
    }

    public static function find($keyword, $limit = 5)
    {
        if (!(static::$http instanceof Client)) {
            static::init();
        }

        $result = static::$http->post("http://www.80s.tw/search", [
            "form_params" => [
                "keyword" => $keyword
            ]
        ]);

        $html = (string)$result->getBody();
        $html_dom = new \HtmlParser\ParserDom($html);
        $lis = $html_dom->find('ul.search_list li');

        $movies = [];

        foreach ($lis as $key => $liItem) {
            if (!is_null($limit) && $key >= $limit) {
                break;
            }
            $a = $liItem->find("a", 0);
            $url = 'http://www.80s.tw' . $a->getAttr('href');
            $title = clear($a->getPlainText());
            $movieItem = [
                'url' => $url,
                'title' => $title
            ];
            /**
             * 爬取具体的电影信息
             */
            $movieItemResponse = static::$http->get($url);
            $movieItemHtml = (string)$movieItemResponse->getBody();
            $movieItemDom = new \HtmlParser\ParserDom($movieItemHtml);
            $urls = [];
            /**
             * 获取电影属性
             */

            $movieItem['description'] = clear($movieItemDom->find("#movie_content")[0]->getPlainText());
            $infoList = $movieItemDom->find("div[class=clearfix] span.span_block");
            $infoLength = count($infoList);
            foreach ($infoList as $index => $infoItem) {
                $nodes = $infoItem->find('span.font_888');
                if ($index >= $infoLength or !isset($nodes[0])) {
                    continue;
                }
                $attrName = $nodes[0];
                $attr = str_replace("：", "", $attrName->getPlainText());
                if (isset(static::$infoMap[$attr])) {
                    $movieItem[static::$infoMap[$attr]] = clear($infoItem->getPlainText());
                }
            }

            /**
             * 获取下载地址
             */
            $downloads = $movieItemDom->find("div#cpdl2list")[0]->find("li.dlurlelement");
            array_shift($downloads);
            array_pop($downloads);
            foreach ($downloads as $download) {
                $a = $download->find("a[rel=nofollow]");
                $urls[] = [
                    'title' => clear($a[0]->getPlainText()),
                    'url' => $a[1]->getAttr("href"),
                ];
            }

            $movieItem["downloads"] = $urls;
            $movies[] = $movieItem;
        }
        return $movies;
    }
}
