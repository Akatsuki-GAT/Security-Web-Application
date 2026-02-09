# Security-Web-Application 02/04/2026

PHP(blade.php) 8.2.12 + JS + CSS + MySQL and Laravel Framework 12.49.0 with Composer 2.9.5 and API named Enzoic in strength checking of Password in Registration

The only feature working as of now is the password strength meter is Enzoic, a client-side password intelligence.
+ Reads the password as the user types
+ Scores strength (weak / medium / strong)

What needs to be done is to:
- Making the next page after login or regsiter doesn't get accessed by URL 
- Adding a demo database for CRUD and update some lines of code to make it Laravel built code
- Deciding whether to add and then finalize Admin route pages route or not as this one was originally users only
- Going back to demo database making sure it is translated to Laravel readablity from its old PHP functions and structure

As of now I, Joshua Ulrik G. Galano perform and plan this task as the packages of making Laravel function is heavy and require lots of tinkering like 
Composer, mySQL and PHP(XAMPP takes care of it) as I don't know if my other members machine spec is available or at a low end that running may crash or not run at all.

The register, login, and localrun is on IAS folder which contain images of it.

# Security-Web-Application 02/09/2026

Features working and done(some parts) are: (Proofs are in Security Feats IMAGES)
- Password hashing using bcrypt in Laravel framework
- Protected routes implemented with bit of role based access- guest and auth (As of now it restricts index.blade.php if user is guest or hasn't logged in or created)
  though some small parts in .blade.php still needs tinkering to work and debug.

Areas that are in progress:
- Patching some pages with protected routes which leds to:
- Admin has its own POV . Both users and admin will share the same except some tweaks that only the admin can access with.
- Login accepts email as of now it only accepts username
