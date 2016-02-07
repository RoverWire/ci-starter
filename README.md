# Codeigniter Boilerplate

[![Codeigniter](https://img.shields.io/badge/Codeigniter-v3.0.4-orange.svg)](http://codeigniter.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v3.3.6-6f5499.svg)](http://getbootstrap.com/)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/91def6b56ce945e2a1a52c99a804cb10)](https://www.codacy.com/app/roverwire/ci-starter)

Starter setup for codeigniter projects that includes:

- Admin panel
- Simple user management for admin access
- Block access after failed attempts (default 15 minutes after 3 attempts)
- Reset access by mail when user forgot password
- HMVC integration
- Access library can be used with other module
- Google analytics integration to show visits graph
- Template library

## Installation

- Clone or download the repo
- Create database for the project and set access on application/config/database.php
- Install composer dependencies
- Access to http://yoursite.com/setup
- Optional: Enable hooks to use whoops on development.

And that's it! Now you can access to /admin area with admin / 123456 credentials.
