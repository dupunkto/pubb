<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<?php if(!$not_found) { ?>
<link rel="canonical" href="<?= canonicalize_url(CANONICAL . $path) ?>">
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?= CANONICAL ?>/skins/<?= LAYOUT_SKIN ?>.css">

<?php if(defined('SITE_FAVICON')) { ?>
<link rel="shortcut icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20100%20100'%3E%3Ctext%20y='.9em'%20font-size='90'%3E<?= SITE_FAVICON ?>%3C/text%3E%3C/svg%3E">
<?php } ?>

<meta name="author" content="<?= esc_attr(AUTHOR_NAME) ?>">
<meta name="description" content="<?= esc_attr(SITE_DESCRIPTION) ?>">
<meta name="generator" content="Pubb v<?= PUBB_VERSION ?>">
<?php if(defined('SITE_COPYRIGHT')) { ?>
<meta name="rights" content="<?= esc_attr(SITE_COPYRIGHT) ?>">
<?php } ?>

<!-- Sitemap, because kinda nice -->
<link rel="sitemap" type="application/xml" title="Sitemap" href="<?= CANONICAL ?>/sitemap.xml">

<!-- RSS, because it's amazing -->
<link rel="alternate" type="application/rss+xml" title="<?= esc_attr(SITE_TITLE) ?> (RSS)" href="<?= CANONICAL ?>/atom.xml">
<link rel="alternate" type="application/atom+xml" title="<?= esc_attr(SITE_TITLE) ?> (Atom)" href="<?= CANONICAL ?>/atom.xml">
<link rel="alternate" type="application/feed+json" title="<?= esc_attr(SITE_TITLE) ?> (JSON)" href="<?= CANONICAL ?>/feed.json">

<!-- Google, please don't mess with my lovingly crafted HTML -->
<meta name="googlebot" content="notranslate" />

<?php $hidden = @$page['visibility'] == 'hidden' ?>

<?php if(NONCOMMERCIAL or $hidden) { ?>
<!-- Block commercial scrapers -->
<meta name="robots" content="noindex, nofollow">
<meta name="pinterest" content="nopin">
<?php } ?>

<?php if(NONAI) { ?>
<!-- Block AI scrapers -->
<meta name="robots" content="noai, noimageai">
<?php } ?>

<?php if(defined('LICENSE')) { ?>
<!-- License -->
<meta name="license" content="<?= esc_attr(LICENSE) ?>">
<meta name="usage-rights" content="<?= esc_attr(LICENSE) ?>">
<?php if(defined('LICENSE_URI')) { ?>
<link rel="license" href="<?= esc_attr(LICENSE_URI) ?>" />
<?php }
} ?>

<link rel="icon" sizes="16x16" href="<?= CANONICAL ?>/favicon.ico">
<link rel="apple-touch-icon" sizes="16x16" href="<?= CANONICAL ?>/favicon.ico">

<!-- Micropub -->
<link rel="micropub" href="<?= MICROPUB_ENDPOINT ?>">
<link rel="media" href="<?= MEDIA_ENDPOINT ?>">

<!-- IndieAuth -->
<link rel="indieauth-metadata" href="<?= AUTH_ENDPOINT ?>?metadata">
<link rel="authorization_endpoint" href="<?= AUTH_ENDPOINT ?>">
<link rel="token_endpoint" href="<?= TOKEN_ENDPOINT ?>">

<!-- Webmention & Pingback -->
<link rel="webmention" href="<?= WEBMENTION_ENDPOINT ?>">
<link rel="pingback" href="<?= PINGBACK_ENDPOINT ?>">
