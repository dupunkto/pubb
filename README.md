# Untitled

This yet-to-be-named project is a prototype for my untitled successor to neopub, temporarily codenamed Pubb for the engine and Pebble for the CMS. It's a playground to explore ways to blend websites, social media and collaboration on the Web, by implementing an [easy](https://gilest.org/indie-easy.html)-to-use website engine powered by various open web standards, such as [micropub](https://www.w3.org/TR/micropub/), [microformats](https://microformats.org/), [webmention](https://www.w3.org/TR/webmention/), [pingback](https://en.wikipedia.org/wiki/Pingback), and (eventually), [ActivityPub](https://www.w3.org/TR/activitypub/).

This is the succcesor to [neopub](https://git.dupunkto.org/neopub), an earlier attempt at building a microblogging platform.

An earlier version of this projects exists, named [neopub/untitled](https://git.dupunkto.org/neopub/untitled). This is an improved and rewritten version of that codebase.

## Features

- [x] Single tenant
- [x] Short posts
- [x] Captioned images
- [x] Captioned code (like gists)
- [x] Replies
- [x] Sending webmentions
- [x] Sending pingbacks
- [x] Receiving webmentions
- [x] Receiving pingbacks
- [x] RSS, Atom & JSON feeds
- [x] Comment section
- [x] IndieAuth
- [x] RSS-only postings
- [x] [@mentions](https://roblog.nl/blog/mentions)
- [x] Volumes
- [x] Skins
- [ ] OpenHeart
- [ ] ActivityPub
- [ ] CLI

### CMS

I've also build a cozy little CMS to power this website engine. The CMS is inspired by earlier versions of Blogger and has a bunch of cool features:

- Clean editor
- Asset management
- Contact management
- Moderating webmentions
- Basic statistics

### CLI

    pub < post.txt
    pub -i image.png
    pub -i graph.png -n
    echo "caption" | pub -i another.png
    echo "caption" | pub -c main.c

Optional -n flag uploads the image but doesn't include it in the feeds, useful for including images in a post.
Optional -c flag copies the url to keyboard. otherwise, write to stdout.

### Eventually?

I'm also thinking about other things, but I've shelved them until I have a working site up-and-running:

- Git integration? I could add an optional git module that would version-manage the data store. I'm not sure whether this is possible using PHP, but it would definitely be cool.

- [#hashtags](https://personal-web.org). The difficulty is that some sort of central service is needed. I'm not sure how ActivityPub handles it, but might be interesting to look into when I build the ActivityPub functionality?

- Access control? Only allowing certain subscribers to see some posts. This would work by giving each subscriber an unique token, which would be stored in a cookie and appended to the query params of the RSS feed.

- Previewing drafts, maybe implemented using the same foundation as access control?

## Goals

Easy publishing from my phone. Hit icon, type something, attach picture, post.

Easy publishing from my laptop. Writing post in Markdown in Helix, pasting
images (automatically uploading to the cdn, with -n flag).

## Non-goals

**Tests**. I get it, they're important for ensuring code quality and confidence in codebases in professional settings. But this is a side-project that I'm building in my spare time, for fun. Don't ruin that for me please.

**Bookmarks, crossposts, likes**. I want to encourage conversation, collaboration and meaningful interaction. Mindlessly double tapping doesn't fit that vision.
