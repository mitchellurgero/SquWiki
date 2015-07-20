title: Home Page
description: The Home Page
keywords: home,page,squwiki

# Home Page #

## Welcome to SquWiki - The clean and modern PHP Wiki! ##

### What is SquWiki? ###
SquWiki is a PHP script that allows one to host a basic, easy to use Wiki. The wiki articles are not editable by the public, and must be edited using some type of notepad like application. This allows SquWiki to run with very low requirements, and no database.

SquWiki came to be because I could not find a solution for my needs, something flat, something simple to install and something that used Markdown and was super easy to use without the need of an exploitable login system.

### Installing SquWiki ###
If you are reading this, then you have this installed already.

### How to use SquWiki ###
SquWiki is very simple and easy to use PHP Wiki script. Everything is written in markdown, which is human readable text to format HTML pages.

Editing the wiki is simple as everything is stored in a flat-file type database of sorts. When you want to create a new page, just simply login to your web server, and create a new file under the "pages" folder called "page_name.md".
Then you will need the following at the top of the file:

    title: Tile of Page
    description: Description of Page
    keywords: Keywords,of,page
    
That will set most of the needed meta data for you so that if a page is shared from the site, places like Google, Facebook, Twitter, etc can grab meta data which makes the search result, or post much better looking.
Once that is in place, you can start building the page to your liking. [Here is where it all started](http://daringfireball.net/projects/markdown/syntax). This will give you the syntax you need to begin building a good-looking, simple, clean, and fast Wiki Site.

If you can understand markdown language then you will be able to use this wiki perfectly fine.

### Security & Page Locking ###
SquWiki supports disabling page edits if public edditing is enabled, and it is really easy to do. 
To lock a page from editing all you have to do is make a file under the lock folder called **page_name.lock**.

### Understanding the SquWiki File System ###
The SquWiki file system is very simple to understand, you have to first realise the folders in it:
- backupPages
- lock
- pages
- css
- js
- application

#### backupPages ####
This folder is where backups of pages are taken when a user presses the "Save" button when editing a wiki page (if public editing is enabled in the config.php file). There is not much to this folder.

#### lock ####
The lock folder. This folder is where you can "lock" pages from editing if you have public editing enabled. If you want to lock a page from editing, simple put a blank file called **page_name.lock** in this folder. This will keep edits from happening to that page.
Please note: the file name in the lock folder needs to match the filename in the pages folder (minus the extension).

#### pages ####
This folder is where you put the wiki pages. The format is simple: **page_name.md**. The file name (without the extension) will be used for the URL, for example: pagename.md will go out to http://domain.com/pagename.

#### css ####
The CSS folder holds the CSS for the Wiki site, style.css is the default, and cannot be changed without modifing the actual Wiki script. But you can modify the style.css to your liking.
For simplicity, style.css has been formatted to style **HTML TAGS** so that any edits made are unified.

#### js ####
This folder is for the javascript you want on the site. **index.js** is automatically put on every page whether it is empty or full of javascript.

#### application ####
The application folder is where the menu.md and aside.md files are stored.
- menu.md: The menu top bar that is displayed on every page
- aside.md: The sidebar displayed on every page

Both support markdown, HTML, and Javascript.

### Using config.php to configure your new Wiki ###
When you open up config.php you will see comments in there regarding how to use everything.