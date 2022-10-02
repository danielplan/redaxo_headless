<?php

use FriendsOfREDAXO\Headless\HeadlessController;
use FriendsOfREDAXO\Headless\Navbuilder\NavbuilderService;

class NavbuilderController extends HeadlessController
{


    public function nav(string $name): array
    {
        return NavbuilderService::getNavbuilder($name);
    }

}
