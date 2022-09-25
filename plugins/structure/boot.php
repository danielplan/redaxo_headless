<?php

use FriendsOfREDAXO\Headless\Serializer;


Serializer::register(\rex_article::class, new ArticleSerializer());
