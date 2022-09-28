<?php

namespace FriendsOfREDAXO\Headless\Structure;

use FriendsOfREDAXO\Headless\Serializer;
use rex_article;
use rex_category;

class NavigationService
{

    public static function buildNavigation(int $depth = 1): array
    {
        $article = Serializer::serializeToArray(rex_article::getSiteStartArticle());
        $article['children'] = static::getChildCategories(rex_category::getRootCategories(true), $depth);
        return $article;
    }

    protected static function getRootArticles(): array
    {
        $articles = [];
        foreach (rex_category::getRootCategories(true) as $category) {
            $articles[] = Serializer::serializeToArray($category->getArticle());
        }
        return $articles;
    }

    protected static function getChildCategories(?array $categories, int $depth): array
    {
        $navigation = [];
        foreach ($categories as $category) {
            $navItem = Serializer::serializeToArray($category->getStartArticle());
            $childArticles = $category->getArticles(true);
            if ($childArticles && count($childArticles) > 1) {
                $navItem['articles'] = static::getChildArticles($childArticles);
            }
            if ($depth > 1) {
                $children = $category->getChildren(true);
                if ($children) {
                    $navItem['children'] = static::getChildCategories($children, $depth - 1);
                }
            }
            $navigation[] = $navItem;
        }
        return $navigation;
    }

    protected static function getChildArticles(array $articles): array
    {
        $result = [];
        foreach ($articles as $article) {
            if ($article->isStartArticle()) {
                continue;
            }
            $result[] = Serializer::serializeToArray($article);
        }
        return $result;
    }
}
