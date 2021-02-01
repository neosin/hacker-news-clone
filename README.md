# Hacker news clone

Hello 👋🏻

We were tasked with making a hacker news imitator using mainly php, so this is my attempt!

## Current featureset

- create an account.

- login.

- logout.

- edit account email, password and biography.

- upload a profile avatar image.

- create new posts with title, link and description.

- edit posts.

- delete posts.

- view most upvoted posts.

- view new posts.

- upvote posts.

- remove upvote from posts.

- comment on a post.

- edit comments.

- delete comments.

- reply comments.

- delete account along with all posts, upvotes and comments.

- search for posts via title and description.

## Missing

- resetting my password with email.

- and probably many other things.

## Instructions

- First of all, clone this repo from

```sh
git clone https://github.com/simonlindstedt/hacker-news-clone.git
```

- Have [PHP](https://www.php.net/) installed

- Navigate to the root of this directory in your terminal of choice

- Launch a php server from that directory

```sh
php -S localhost:8000
```

- Then open up your web browser och choice and enter localhost:8000 as the URL

- Approach the rest just like a normal website!

## Testers:

- Rickard Segerkvist

- Erik White

## Code Reviewer (Agnes 'Bitte' Binett):

- The only thing I can point out in terms of improvement is that maybe the headings are a bit low in contrast.

- Great job with the functions, minimizes the amount of code in your other files.

- Looks very good in mobile view, but the posts get real wide in desk-top view.. so one could perhaps put a max-width on those?

- Nice search function! :)

- Fun to be able to look at other user's profiles.

- Maybe the 'submit post' and 'search news' could be inline in desk-top mode

- Saves space when you use the post description as the link title, but some users maybe want to see which website they're visiting?

- Very clean design and easy to navigate!

- Grasping at straws to come up with things to comment on, but one last thing could be to move submit post down towards the posts so it's even esier to navigate 

- I'm superimpressed, really well done! :)

## New features added by [Lucas](https://github.com/pnpjss):

[Reset password via email and comment upvotes](https://github.com/simonlindstedt/hacker-news-clone/pull/2)
