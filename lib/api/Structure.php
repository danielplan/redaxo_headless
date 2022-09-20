<?php


class rex_api_structure extends Api
{
    public function getNavigation()
    {
        $rootArticle = rex_article::getSiteStartArticle();


        $rootCategories = rex_category::getRootCategories();



        return [
            'id' => $rootArticle->getId(),
            'name' => $rootArticle->getName(),
            'url' => $rootArticle->getUrl(),
        ];

    }
}

