**Setup instructions**

**The following steps require this software to be installed on your machine:**
- PHP 8 (or higher)
- Composer
- Docker
- Node
- NPM

run `composer install`

run `npm install`

**You will need three tabs open in your terminal for the following section:**

run `./vendor/bin/sail up`

run `./vendor/bin/sail artisan migrate:fresh --seed` (adds entries to the database for users with roles and tasks)

run `npm run dev`

Navigate to http://localhost
