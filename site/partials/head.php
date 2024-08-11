<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<!-- Sitemap, because kinda nice -->
<link rel="sitemap" type="application/xml" title="Sitemap" href="<?= CANONICAL ?>/sitemap.xml">

<!-- RSS, because it's amazing -->
<link rel="alternate" type="application/rss+xml" title="<?= SITE_TITLE ?> (RSS)" href="<?= CANONICAL . "/rss.xml" ?>">
<link rel="alternate" type="application/atom+xml" title="<?= SITE_TITLE ?> (Atom)" href="<?= CANONICAL . "/atom.xml" ?>">
<link rel="alternate" type="application/feed+json" title="<?= SITE_TITLE ?> (JSON)" href="<?= CANONICAL . "/feed.json" ?>">

<link rel="stylesheet" type="text/css" href="/main.css">

<!-- Micropub, IndieAuth, Webmention & Pingback -->
<link rel="micropub" href="<?= MICROPUB_ENDPOINT ?>">
<link rel="media" href="<?= MEDIA_ENDPOINT ?>">
<link rel="indieauth-metadata" href="<?= AUTH_ENDPOINT ?>?metadata">
<link rel="authorization_endpoint" href="<?= AUTH_ENDPOINT ?>">
<link rel="token_endpoint" href="<?= TOKEN_ENDPOINT ?>">
<link rel="webmention" href="<?= WEBMENTION_ENDPOINT ?>">
<link rel="pingback" href="<?= PINGBACK_ENDPOINT ?>">
