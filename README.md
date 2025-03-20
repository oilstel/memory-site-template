# memory site template

this is a template for creating your own memory site

### what is a memory site

- mostly written for oneself, leaning more towards a diary than a blog.
- a way of documenting experiences, memories, ideas, and realizations.
- contains both short and long form writing

### about this memory site

this memory site template is a fork of elliott's memory.elliott.computer site[1]. every post holds the same weight. it is loosey inspoired by the zettelkasten system[2], are.na[3], zinzy.website[4], and Piper Haywood's[5] website. You can read more about my interest in memory sites on my memory site[6].

[1] https://memory.elliott.computer
[2] https://en.wikipedia.org/wiki/Zettelkasten
[3] https://are.na
[4] https://zinzy.website
[5] https://piperhaywood.com/
[6] https://memory.elliott.computer/posts/memory-sites-special-fish-in-2025-pin-moments

### customization

i also think designing or customizing the cms is part of what make memory site useful on a personal level. they reflect how the writer thinks and sees the world. they are more than just the viewable portion.

### how to use this template

the hope is that this memory site can be used as a starter template so that you don't have to start from scratch. feel free to use the parts that work for you and remove the parts that don't.

---

## technical information

### cms

A content managment system or cms for short allows you to publish to your website without having to work with html directly. often this is useful when are working with a client where they operate in a no-code bubble. this memory site uses kirby cms. kirby is a cms that uses php to create "flat files" of your data. in this instance, kirby builds a static site that you can publish on the www.

### using the static site generator

on most pages in the cms, you'll see a "Generate a static version of the site" button. It uses a Kirby plugin called "Static Site Generator."[1] By clicking this it will generate your entire site as static (html, css, js) website in `/static`. you can upload your static site uses ftp or to github pages.

[1] https://plugins.getkirby.com/d4l/static-site-generator

### using kirby on a server

it is also possible to run kirby directly on a server (without the static site generator). it is a bit more setup and depends a lot on which server you are using. this is not covered here. using kirby directly on a server will allow you to publish more instantly to your live website, login from any computer, also create user accounts that can be shared.

