<?php

namespace FriendsOfREDAXO\Headless\Structure;

use FriendsOfREDAXO\Headless\Serializer;
use rex_article;
use rex_category;

class PagesService

{

    public static function buildNavigation(int $depth = 1): array
    {
        $article = Serializer::serializeToArray(rex_article::getSiteStartArticle());
        $article['children'] = static::getSubNavigation(rex_category::getRootCategories(true), $depth);
        return $article;
    }

    protected static function getRootArticles(): array
    {
        return rex_article::getRootArticles(true);
    }

    protected static function getSubNavigation(?array $categories, int $depth): array
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
                    $navItem['children'] = static::getSubNavigation($children, $depth - 1);
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

    public static function getPages(): array
    {
        $pages = static::getRootArticles();
        foreach (rex_category::getRootCategories(true) as $category) {
            $pages = array_merge($pages, static::getChildPages($category));
        }
        return $pages;
    }

    protected static function getChildPages(?rex_category $category): array
    {
        $pages = [];
        if ($category) {
            $pages = array_merge($pages, $category->getArticles(true));
            foreach ($category->getChildren(true) as $child) {
                $pages = array_merge($pages, static::getChildPages($child));
            }
        }
        return $pages;
    }
}
