# What is this?

This is a little sample code to show my familiarity and capability with some things - namely LLMs, tool, and agents in Laravel. It's also a setup where I'll be able to show Docker setup, design patterns, and Vue.js examples. Nothing here is production ready - I'm not dealing with security or anything, though I may throw that in later - it's not like it take a long time - but for the moment this is an example of other things.

## Usage
More is coming, but right now there are a couple different things you can do. There is a containerized version that will be pushed soon - I just haven't had time to test it yet. In the meantime, here you go:

- Have MySQL and PHP installed to a linux distro
  - I wrote and tested this on a windows machine using Ubuntu 24 in WSL2, so it should work on that, Ubuntu, OSX, or Debian.
  - You'll need PHP 8.4+ and MySQL 8+ installed for this to work - if you don't have that, give me a couple days until I test and post the docker setup.
- Clone or fork the repo
- Run `composer install`
- Copy `.env.example` to `.env` and fill out the relevant information:
  - Use the `AI_PROVIDER` and `AI_MODEL` values to let the system know which LLM provider and model you're using
    - This has been tested using `openai:gpt-40-mini` and `gemini:gemini-3-flash-preview`
	- See `/vendor/prism-php/prism/src/Enums/Provider.php` for the string repesentations of LLM providers, and add models to `app/Enums/AiModelEnum.php` if you're using something else
  - Fill out the `*_API_KEY` and `*_PROJECT` values as appropriate to your provider

- Open your terminal
- Run `php artisan migrate`
- Run `php atisan db:seed --class=BookSeeder` to create a (very) minimal amount of data
- Here's where you've got a choice:
  - If you just trust me, run `php artisan test --group=ai-test`
  - If you want to play around, run `php artisan explore:bookstore`
- If you run the `explore:bookstore` command when the textbox appears, ask a question about a book - describe the plot, have movies ever been made out of a book, etc...
   - The bot has memory, so tell it your name and (depending on the provider/model) it'll either automatically use your name in the replies or you can ask it your name and it'll tell you
   - Chat about the book and any sequels, themes, adaptations, etc. Go freakin' nuts
- Right now I'm tired so I've only written a book seeder for the novels 'Neuromancer' by William Gibson and 'Killer Swell' by Jeff Shelby (both great and highly recommended but very different novels)
   - Ask about the availability of both of these books - it'll respond correctly about the availability of either
     - Read the seeders or query the database after running `php artisan migrate` to see which of the two is in stock and which isn't
	 - The bot should tell you correctly which book is in stock based on the number of copies you want to buy

- If you have Apache or Nginx installed, create a virtual host file for the bookstore-ai directory
- In your browser of choice, visit the home page
- Chat with the bot in the web UI

## Testing
As of Februrary 1, 2026 there are some feature tests in the `ai-test` group that checks the following:
  - Creating a conversation
  - Adding to an existing conversation
  - Checking inventory availability and quantity based on title
