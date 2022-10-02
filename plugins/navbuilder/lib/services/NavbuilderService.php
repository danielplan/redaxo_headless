<?php

namespace FriendsOfREDAXO\Headless\Navbuilder;

class NavbuilderService
{

    public static function getNavbuilder(string $name): array
    {
        $menuItems = \rex_navbuilder::getStructure($name);
        if(!$menuItems) {
            throw new \NotFoundException();
        }
        \rex_extension::registerPoint(new \rex_extension_point('HEADLESS_NAVBUILDER', $menuItems, [
            'name' => $name,
        ]));

        foreach ($menuItems as $key => $item) {
            if ($item['type'] === 'intern') {
                $menuItems[$key]['article'] = \rex_article::get($item['id']);
            }
        }
        return $menuItems;
    }

}
