Clean CMS
=========

**This is an early, experimental release.**

Allow content admins to create rich pages without needing to touch HTML.

![content management](https://cloud.githubusercontent.com/assets/1542197/3476621/7646c672-0303-11e4-9fb6-eeb20658b6ae.jpg)

### Installation

Install using modman:

    modman clone git@github.com:kalenjordan/clean-cms.git
    
There's a markdown library that's required for the markdown content block:

    https://github.com/michelf/php-markdown
    
### How it works

When editing a CMS Page, there will be a tab called Content Blocks.  In there, the content admin can add / edit / delete fieldsets to the page.  When adding a fieldset, they can select which type they want.  They'll be able to select from types like:

  - Simple paragraph
  - Image with title and paragraph
  - Email signup
  - Video
  - Full Width Image
  - etc.

Each of those content block types will have it's own selection of simple field types - text fields, text areas, image uploads, etc.

They can fill out the content and save it.

This will generate HTML.  You will need to write your own CSS to style the pages.

### To Do

  - Add support for more content types - there are just a couple really basic ones right now
  - Ajaxify the content block editor
