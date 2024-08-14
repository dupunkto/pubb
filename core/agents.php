<?php
// Holds listings of known User-Agents.

namespace agents;

define('CORPORATE_AGENTS', [
  "Googlebot",
  "Googlebot-Image",
  "Googlebot-News",
  "Googlebot-Video",
  "Googlebot-Mobile",
  "AdsBot-Google",
  "AdsBot-Google-Mobile",
  "GoogleOther",
  "bingbot",
  "redditbot",
  "Twitterbot",
  "FacebookBot",
  "Applebot",
  "Amazonbot",
  "Pinterestbot",
  "YandexBot",
  "YandexImages",
  "Yahoo! Slurp"
]);

define('AI_AGENTS', [
  "Applebot-Extended",
  "Google-Extended",
  "ChatGPT",
  "ChatGPT-User",
  "GPTBot",
  "GPTBot-User",
  "OAI-Searchbot",
  "Meta-ExternalFetcher",
  "Meta-ExternalAgent",
  "Omgilibot",
  "Omgili",
  "omgili",
  "Claude-Web",
  "ClaudeBot",
  "PerplexityBot",
  "Bytespider",
  "Diffbot",
  "YouBot",

  # Misc AI training bots, known or suspected
  "anthropic-ai",
  "cohere-ai",
  "Timpibot",
  "The Knowledge AI",
  "aiHitBot",
  "ImagesiftBot"
]);

function is_corporate() {
  return in_array($_SERVER['HTTP_USER_AGENT'], CORPORATE_AGENTS);
}

function is_ai() {
  return in_array($_SERVER['HTTP_USER_AGENT'], AI_AGENTS);
}

function is_blocked() {
  return (NONCOMMERCIAL and is_corporate()) or (NONAI and is_ai());
}
