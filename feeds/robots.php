<?php
// robots.txt to block unwanted visitors.

header("Content-Type: text/plain; charset=UTF-8");

?>
Sitemap: <?= CANONICAL ?>/sitemap.xml

<?php if(NONCOMMERCIAL) { ?>
# This site is non-commercial and doesn't want anything
# to do with the Corporate Web.

User-agent: Googlebot
User-agent: Googlebot-Image
User-agent: Googlebot-News
User-agent: Googlebot-Video
User-agent: Googlebot-Mobile
User-agent: AdsBot-Google
User-agent: AdsBot-Google-Mobile
User-agent: GoogleOther
User-agent: bingbot
User-agent: redditbot
User-agent: Twitterbot
User-agent: FacebookBot
User-agent: Applebot
User-agent: Amazonbot
User-agent: Pinterestbot
User-agent: YandexBot
User-agent: YandexImages
User-agent: Yahoo! Slurp
Disallow: /
<?php } ?>

<?php if(NONAI) { ?>
# This site is opposed to the unrequested, unwanted and
# unethical scraping of the Personal Web in order to "train"
# Carbon-hungry highschoolers.

User-agent: Applebot-Extended
User-agent: Google-Extended
User-agent: ChatGPT
User-agent: ChatGPT-User
User-agent: GPTBot
User-agent: GPTBot-User
User-agent: OAI-Searchbot
User-agent: Meta-ExternalFetcher
User-agent: Meta-ExternalAgent
User-agent: Omgilibot
User-agent: Omgili
User-agent: omgili
User-agent: Claude-Web
User-agent: ClaudeBot
User-agent: PerplexityBot
User-agent: Bytespider
User-agent: Diffbot
User-agent: YouBot
Disallow: /

# Misc AI training bots, known or suspected
User-agent: anthropic-ai
User-agent: cohere-ai
User-agent: Timpibot
User-agent: The Knowledge AI
User-agent: aiHitBot
User-agent: ImagesiftBot
Disallow: /
<?php } ?>
