

## About

Chuck Norris joke app is the assessment practice task.

It takes the list of emails from the user, and then sends random Chuck Norris joke to the given addresses. On the backend part, email addresses are sorted and send altogether with the random joke to the frontend.

Email sending is scheduled through the laravel job.

## Usage

- Clone this repository: **git clone https://github.com/boby85/chucknorris.git chucknorris**
- Run: **composer install & npm install**
- Execute migrations: **artisan migrate**
- Run the application from the app root folder: **./vendor/bin sail up & npm run dev**
  
- (Docker must be installed in order to user laravel sail images).
- For the email job to work execute listener locally: **artisan queue:listen**

## Software

- <p><a href="https://laravel.com" target="_blank">Laravel Framework 9.24.0 </a></p>
- <p><a href="https://vuejs.org/" target="_blank">VueJS 3.2.36 </a></p>
      
