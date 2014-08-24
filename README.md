rmba
====

This responsive micro-blogging app has been created as partially an excercise and partially a useful tool for my own purposes.

It has been built using plain PHP (5.4), Twig php templating engine, CouchDB, YUI and PureCSS. There will be more features added in the near future, namely a post-viewing history tracer and layout customizer for the readers.

App is ready to use, all you will need is to have CouchDB set up somewhere. By default PHP-CouchDB connector used in the app expects the database to be named "posts" - feel free to change it if you are planning to use the blog.

The structure of the post that I followed when writing the app goes like this (aside of the values added as default by CouchDB):

{
   "post_title": "...",
   "post_content": "...",
   "post_author":"...",
   "post_cat": "...",
   "post_descr": "...",
   "date_published": "...", // whatever format suits you, CouchDB isn't picky
   "post_slug": "..." // I used dashed post title value here
}
