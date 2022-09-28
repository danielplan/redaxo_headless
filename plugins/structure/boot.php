<?php

use FriendsOfREDAXO\Headless\Serializer;


Serializer::register(\rex_article::class, new ArticleSerializer());
Serializer::register(\rex_article_slice::class, new ArticleSliceSerializer());
