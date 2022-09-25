<?php


use FriendsOfREDAXO\Headless\HeadlessController;
use FriendsOfREDAXO\Headless\Serializer;


class StructureController extends HeadlessController
{
    /**
     * ENDPOINT
     * retrieve navigation structure
     *
     * @param int $depth
     *
     * @return array
     */
    public function navigation(int $depth = 1): array
    {
        $article             = Serializer::serializeToArray(rex_article::getSiteStartArticle());
        $article['children'] = $this->getCategories(rex_category::getRootCategories(true), $depth);
        return $article;
    }

    /**
     * ENDPOINT
     * retrieve article data
     *
     * @param int $id
     *
     * @return array
     */
    public function article(int $id): array
    {
        return Serializer::serializeToArray(rex_article::get($id));
    }

    private function getCategories(?array $categories, int $depth): array
    {
        $navigation = [];
        foreach ($categories as $category) {
            $navItem       = Serializer::serializeToArray($category->getStartArticle());
            $childArticles = $category->getArticles(true);
            if ($childArticles && count($childArticles) > 1) {
                $navItem['articles'] = $this->getChildArticles($childArticles);
            }
            if ($depth > 1) {
                $children = $category->getChildren(true);
                if ($children) {
                    $navItem['children'] = $this->getCategories($children, $depth - 1);
                }
            }
            $navigation[] = $navItem;
        }
        return $navigation;
    }

    private function getChildArticles(array $articles): array
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

