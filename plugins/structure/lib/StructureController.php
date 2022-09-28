<?php


use FriendsOfREDAXO\Headless\HeadlessController;
use FriendsOfREDAXO\Headless\Structure\ArticleService;
use FriendsOfREDAXO\Headless\Structure\NavigationService;


class StructureController extends HeadlessController
{
    /**
     * retrieve navigation structure
     *
     * @param int $depth
     *
     * @return array
     */
    public function navigation(int $depth = 1): array
    {
        return NavigationService::buildNavigation($depth);
    }

    /**
     * retrieve article data
     *
     * @param int $id
     *
     * @return rex_article
     */
    public function article(int $id): rex_article
    {
        return ArticleService::getArticle($id);
    }

    /**
     * retrieve article slices
     *
     * @param int $articleId
     *
     * @return array
     */
    public function slices(int $articleId): array
    {
        return ArticleService::getArticleSlices($articleId);
    }

}

