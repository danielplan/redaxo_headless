<?php

namespace FriendsOfREDAXO\Headless\Structure;

use rex_article;
use rex_article_slice;

class ArticleService
{

    public static function getArticle(int $id): rex_article
    {
        $article = rex_article::get($id);
        if(!$article) {
            throw new \NotFoundException();
        }
        return \rex_extension::registerPoint(new \rex_extension_point('HEADLESS_ARTICLE', $article, [
            'id' => $id,
        ]));
    }

    public static function getArticleSlices(int $articleId): array
    {
        $slices = rex_article_slice::getSlicesForArticle($articleId);
        if(!$slices) {
            throw new \NotFoundException();
        }
        return \rex_extension::registerPoint(new \rex_extension_point('HEADLESS_SLICES', $slices, [
            'articleId' => $articleId,
        ]));
    }

}
