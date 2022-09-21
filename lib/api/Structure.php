<?php


class rex_api_structure extends Api
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
        $article             = $this->serializeArticle(rex_article::getSiteStartArticle());
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
        return $this->serializeArticle(rex_article::get($id), true);
    }

    private function getCategories(?array $categories, int $depth): array
    {
        $navigation = [];
        foreach ($categories as $category) {
            $navItem       = $this->serializeArticle($category->getStartArticle());
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
            $result[] = $this->serializeArticle($article);
        }
        return $result;
    }

    private function serializeArticle(rex_article $article, bool $slices = false): array
    {
        $data = [
            'id'               => $article->getId(),
            'name'             => $article->getName(),
            'url'              => $article->getUrl(),
            'siteStartArticle' => $article->isSiteStartArticle(),
            'startArticle'     => $article->isStartArticle(),
            'online'           => $article->isOnline(),
        ];
        if ($slices) {
            $data['slices'] = $this->serializeSlices($article);
        }
        return $data;
    }

    private function serializeSlices(rex_article $article): array
    {
        $slices        = [];
        $articleSlices = rex_article_slice::getSlicesForArticle($article->getId());
        foreach ($articleSlices as $slice) {
            $result           = [];
            $result['id']     = $slice->getId();
            $result['module'] = $slice->getModuleId();
            for ($i = 1; $i <= 20; $i++) {
                if ($slice->getValue($i)) {
                    $result['value_' . $i] = $slice->getValue($i);
                }
                if ($slice->getMedia($i)) {
                    $result['media_' . $i] = $slice->getMedia($i);
                }
                if ($slice->getMediaList($i)) {
                    $result['mediaList_' . $i] = explode(',', $slice->getMediaList($i));
                }
                if ($slice->getLink($i)) {
                    $result['link_' . $i] = $slice->getLink($i);
                }
                if ($slice->getLinkList($i)) {
                    $result['linkList_' . $i] = $slice->getLinkList($i);
                }
            }

            $slices[] = $result;
        }
        return $slices;
    }
}

