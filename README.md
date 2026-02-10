# Security-Web-Application 02/04/2026

PHP(blade.php for front end of things) 8.2.12 + JS + CSS + MySQL and Laravel Framework 12.49.0 with Composer 2.9.5 and API named Enzoic in strength checking of Password in Registration

The only feature working as of now is the password strength meter is Enzoic, a client-side password intelligence.
+ Reads the password as the user types
+ Scores strength (weak / medium / strong)

What needs to be done is to:
- Making the next page after login or regsiter doesn't get accessed by URL 
- Adding a demo database for CRUD and update some lines of code to make it Laravel built code
- Deciding whether to add and then finalize Admin route pages route or not as this one was originally users only
- Going back to demo database making sure it is translated to Laravel readablity from its old PHP functions and structure

As of now I, Joshua Ulrik G. Galano perform and plan this task as the packages of making Laravel function is heavy and require lots of tinkering like 
Composer, mySQL and PHP(XAMPP takes care the two of em) as I don't know if my other members machine spec is available or at a low end that running may crash or not run at all.

The register, login, and localrun is on IAS folder which contain images of it.

# Security-Web-Application 02/09/2026

Features working and done(some parts) are: (Proofs are in Security Feats IMAGES)
- Password hashing using bcrypt in Laravel framework
- Protected routes implemented with bit of role based access- guest and auth (As of now it restricts index.blade.php if user is guest or hasn't logged in or created)
  though some small parts in .blade.php still needs tinkering to work and debug.

Areas that are in progress:
- Patching some pages with protected routes which leds to:
- Admin has its own POV . Both users and admin will share the same except some tweaks that only the admin can manipulate with.
- Login accepts email as of now it only accepts username

#SETUP and RUN
- Creating a proj make sure it's in a designated folder you want it to be and is empty for example of mine- C/xampp/htdocs/Project_Name in VSC
- Then type in VSC terminal (Ctrl + J) 'composer create project laravel/laravel . 'and hit enter to install it on a current file directory stated above
- Once it's done on same VSC terminal type 'php artisan serve' and hit enter and do quicktest typing on your browser 'localhost:8000' which sends you a sample Laravel Page
- Once things clear and if you have a sample.html page make sure it converts to sample.blade.php (Note: The html format still works on .blade.php)
- After that modify the web.php to make the '/' from: return view('home'); which is a default laravel setting to- return view('sample.html') to see your own webpage(In this proj I decided to have '/name_of_the_page' instead of just '/' for ease of reading and looking for.
- If database is now involved make sure mySQL and Apache(for reading if the actual DB function works) is running in XAMPP or any programs that functions like XAMPP.
  

#HOW DO WE IDENTIFY WHO's WHO? (Users that are(n't) login, Admin)

1. Unauthenticated Users (Guests)
+ A guest is any visitor who is not logged in:
- The application does not rely on Blade’s @guest directive which I used for a brief time but decided to go the routing level route which
- Acts as aaccess control is enforced at the routing level in web.php
- Any unauthenticated user attempting to access protected routes (/index) is automatically redirected to the login page if they don't have an account they can register as well.

Identification method:
- Laravel auth middleware located in web.php
- If authentication fails, Laravel redirects the user to /login

Capabilities:
- Cannot access /index or any authenticated pages
- Cannot create, edit, resolve, or delete items
- Cannot contact item owners



2. Authenticated Users (Regular Users)
+ A regular user is someone who has successfully logged in but does not have administrator privileges.

Identification method:
- auth middleware confirms the user session
- User role flag in the database (is_admin = 0)
- is checked using: 'Auth::user()->IsAdmin() === false'


Capabilities:
- Access /index
- Create lost and found posts
- Edit, resolve, or delete only their own items
- Contact other users regarding items
- Ownership enforcement: 'auth()->id() === $item->user_id'

3. Administrators (Admins)
+ An administrator is a logged-in user with elevated privileges.

Identification method:
- Must pass auth middleware which is
- Role flag in the database (is_admin = 1)
- Verified using: 'Auth::user()->IsAdmin() === true'


Capabilities:
- Access all items (including resolved posts)
- Resolve or delete any item
- Moderate user-generated content post
- Access admin-only routes


4. Route-Based Enforcement (web.php)
+ Authorization is enforced before controllers are reached, using middleware groups:
- auth → blocks unauthenticated users and redirects them to login
- auth + admin → restricts access to administrators only



No protected route can be accessed without authentication

Role checks remain consistent across the application

Security is centralized and predictable
