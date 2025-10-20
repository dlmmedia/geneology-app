<p align="center"><img src="https://genealogy.kreaweb.be/img/genealogy.svg" width="300px" alt="Genealogy"/></p>

# Genealogy

![](https://img.shields.io/badge/PHP-8.3-informational?style=flat&logo=php&color=4f5b93)
![](https://img.shields.io/badge/Laravel-12-informational?style=flat&logo=laravel&color=ef3b2d)
![](https://img.shields.io/badge/Alpine.js-3-informational?style=flat&logo=Alpine.js&color=8BC0D0)
![](https://img.shields.io/badge/Livewire-3.6-informational?style=flat&logo=Livewire&color=fb70a9)
![](https://img.shields.io/badge/Filament-4.0-informational?style=flat&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgeG1sbnM6dj0iaHR0cHM6Ly92ZWN0YS5pby9uYW5vIj48cGF0aCBkPSJNMCAwaDQ4djQ4SDBWMHoiIGZpbGw9IiNmNGIyNWUiLz48cGF0aCBkPSJNMjggN2wtMSA2LTMuNDM3LjgxM0wyMCAxNWwtMSAzaDZ2NWgtN2wtMyAxOEg4Yy41MTUtNS44NTMgMS40NTQtMTEuMzMgMy0xN0g4di01bDUtMSAuMjUtMy4yNUMxNCAxMSAxNCAxMSAxNS40MzggOC41NjMgMTkuNDI5IDYuMTI4IDIzLjQ0MiA2LjY4NyAyOCA3eiIgZmlsbD0iIzI4MjQxZSIvPjxwYXRoIGQ9Ik0zMCAxOGg0YzIuMjMzIDUuMzM0IDIuMjMzIDUuMzM0IDEuMTI1IDguNUwzNCAyOWMtLjE2OCAzLjIwOS0uMTY4IDMuMjA5IDAgNmwtMiAxIDEgM2gtNXYyaC0yYy44NzUtNy42MjUuODc1LTcuNjI1IDItMTFoMnYtMmgtMnYtMmwyLTF2LTQtM3oiIGZpbGw9IiMyYTIwMTIiLz48cGF0aCBkPSJNMzUuNTYzIDYuODEzQzM4IDcgMzggNyAzOSA4Yy4xODggMi40MzguMTg4IDIuNDM4IDAgNWwtMiAyYy0yLjYyNS0uMzc1LTIuNjI1LS4zNzUtNS0xLS42MjUtMi4zNzUtLjYyNS0yLjM3NS0xLTUgMi0yIDItMiA0LjU2My0yLjE4N3oiIGZpbGw9IiM0MDM5MzEiLz48cGF0aCBkPSJNMzAgMThoNGMyLjA1NSA1LjMxOSAyLjA1NSA1LjMxOSAxLjgxMyA4LjMxM0wzNSAyOGwtMyAxdi0ybC00IDF2LTJsMi0xdi00LTN6IiBmaWxsPSIjMzEyODFlIi8+PHBhdGggZD0iTTI5IDI3aDN2MmgydjJoLTJ2MmwtNC0xdi0yaDJsLTEtM3oiIGZpbGw9IiMxNTEzMTAiLz48cGF0aCBkPSJNMzAgMThoNHYzaC0ydjJsLTMgMSAxLTZ6IiBmaWxsPSIjNjA0YjMyIi8+PC9zdmc+&&color=fdae4b&link=https://filamentphp.com)

![Latest Stable Version](https://img.shields.io/github/release/MGeurts/genealogy)

[![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg?logo=paypal)](https://www.paypal.me/MGeurtsKREAWEB)
[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-orange.svg?logo=buy-me-a-coffee&logoColor=white)](https://buymeacoffee.com/MGeurts)

## About this project

<b>Genealogy</b> is a free and open-source family tree PHP application to record family members and their relationships, build with Laravel 12.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-000a.webp" class="rounded" alt="Genealogy-000a"/>
<img src="https://genealogy.kreaweb.be/img/help/genealogy-020a.webp" class="rounded" alt="Genealogy-020"/>

This <b>TallStack</b> application is build using :

<ul>
    <li><a href="https://laravel.com/" target="_blank">Laravel</a> 12</li>
    <li><a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream</a> 5 (featuring <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a>)</li>
    <li><a href="https://livewire.laravel.com/" target="_blank">Livewire</a> 4</li>
    <li><a href="https://alpinejs.dev/" target="_blank">Alpine.js</a> 3</li>
    <li><a href="https://tailwindcss.com/" target="_blank">Tailwind CSS</a> 4</li>
    <li><a href="https://tallstackui.com//" target="_blank">TallStackUI</a> 2 (featuring <a href="https://tabler.io/icons" target="_blank">Tabler Icons</a>)</li>
    <li><a href="https://filamentphp.com//" target="_blank">Laravel Filament</a> 4 (only <a href="https://filamentphp.com/docs/4.x/tables/overview/" target="_blank">Table Builder</a>)</li>
</ul>

<img src="https://genealogy.kreaweb.be/img/logo/tallstack.webp" class="rounded" alt="tall-stack"/>

### Logic concept

1. A person can have 1 biological father (1 person, based on <b>father_id</b>)
2. A person can have 1 biological mother (1 person, based on <b>mother_id</b>)
3. A person can have 1 set of parents, biological or not (1 couple of 2 people, based on <b>parents_id</b>)

4. A person can have 0 to many biological children (n people, based on father_id/mother_id)
5. A couple can have 0 to many (plus) children (based on <b>parents_id as a couple</b> or <b>father_id/mother_id individually</b>)

6. A person can have 0 to many partners (n people), being part of 0 to many couples (opposite or same biological sex)
7. A person can be part of a couple with the same partner multiple times (remarriage or reunite)

8. A person can have 0 to many siblings (n people) (based on <b>parents_id as a couple</b> or <b>father_id/mother_id individually</b>)

9. A couple can be married or not, still together or separated in the meantime

### Requirements

<ul>
    <li>
        The application must be served in HTTPS mode, not in HTTP.<br/>
    </li>
    <li>
        At least <a href="https://www.php.net/" target="_blank">PHP</a> 8.3, supporting Laravel 12.<br/>
    </li>
    <li>
        At least <a href="https://www.mysql.com/" target="_blank">MySQL</a> 8.0.1 or <a href="https://mariadb.com/" target="_blank">MariaDB</a> 10.2.2 or an equivalent database, supporting <a href="https://dev.mysql.com/doc/refman/8.0/en/with.html" target="_blank">Recursive Common Table Expressions</a>.
    </li>
</ul>

### License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Demo

<a href="https://genealogy.kreaweb.be/" target="_blank">https://genealogy.kreaweb.be/</a>

<p>This demo has 2 family trees implemented, <b>BRITISH ROYALS</b> and <b>KENNEDY</b>.</p>

<table>
    <thead>
        <tr>
            <th>E-mail</th>
            <th>Password</th>
            <th>Purpose</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>administrator@genealogy.test</b></td>
            <td>password</td>
            <td>to access teams <b>BRITISH ROYALS</b> and <b>KENNEDY</b> as team <b>owner</b></td>
        </tr>
        <tr>
            <td><b>manager@genealogy.test</b></td>
            <td>password</td>
            <td>to access team <b>BRITISH ROYALS</b> as <b>manager</b></td>
        </tr>
        <tr>
            <td><b>editor@genealogy.test</b></td>
            <td>password</td>
            <td>to access team <b>KENNEDY</b> as <b>editor</b></td>
        </tr>
        <tr>
            <td><b>member_1@genealogy.test</b></td>
            <td>password</td>
            <td>to access team <b>BRITISH ROYALS</b> as normal <b>member</b></td>
        </tr>
        <tr>
            <td><b>member_4@genealogy.test</b></td>
            <td>password</td>
            <td>to access team <b>KENNEDY</b> as normal <b>member</b></td>
        </tr>
        <tr>
            <td><b>developer@genealogy.test</b></td>
            <td>password</td>
            <td>to access options reserved for the <b>developer</b>, like the <b>user management</b> and access to <b>all persons</b> in <b>all teams</b></td>
        </tr>
    </tbody>
</table>

## Roles & permissions

### Teams & Users

<table>
    <thead>
        <tr>
            <th style="text-align:left">Role</th>
            <th style="text-align:left">Model</th>
            <th style="text-align:left">Permissions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="3"><b>Team owner</b></td>
            <td>personal team</td>
            <td>update, invite members</td>
        </tr>
        <tr>
            <td>non-personal team</td>
            <td>read, update, delete, invite members, transfer ownership</td>
        </tr>
        <tr>
            <td>new team</td>
            <td>create</td>
        </tr>
        <tr>
            <td rowspan="3"><b>Team member</b></td>
            <td>personal team</td>
            <td>update, invite members</td>
        </tr>
        <tr>
            <td>non-personal team</td>
            <td>accept membership, read, leave</td>
        </tr>
        <tr>
            <td>new team</td>
            <td>create</td>
        </tr>
    </tbody>
</table>

### Persons & Couples

<table>
    <thead>
        <tr>
            <th style="text-align:left">Role</th>
            <th style="text-align:left">Model</th>
            <th style="text-align:left">Permissions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="2"><b>Administrator</b></td>
            <td>person</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Manager</b></td>
            <td>person</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Editor</b></td>
            <td>person</td>
            <td>create, read, update</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Member</b></td>
            <td>person</td>
            <td>read</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>read</td>
        </tr>
    </tbody>
</table>

## Features

<ul>
    <li>Light/Dark theme</li>
    <li>Fully responsive</li>
    <li>Multi-language, language setting saved in authenticated users profile</li>
    <li>Multi-timezone, timezone setting saved in authenticated users profile</li>
    <li>Multi-tenancy by Laravel Jetstream Teams, including Transfer Team Ownership</li>
    <li>Security through Laravel Jetstream Teams Roles & Permissions, 2FA & API can be enabled</li>
    <li>Offcanvas menu</li>
    <li>Multiple image upload with possibility of watermarking, photo carousel with navigation</li>
    <li>Multiple documents upload</li>
</ul>

### Special features

<p>This application has a built-in <b>Backup Manager</b> :
    <ul>
        <li>Backups can be initiated and managed manually</li>
        <li>Backups can be scheludeld by issuing a cron job on your development or production server</li>
        <li>An e-mail will be send after each backup</li>
   </ul>
</p>

<p>This application has a built-in <b>Log Viewer</b>, on demand showing :
    <ul>
        <li>INFO    : All scheduled backups</li>
        <li>DEBUG   : All executed requests (off by default)</li>
        <li>DEBUG   : All executed database queries (off by default)</li>
        <li>WARNING : All detected slow (> 500 ms) queries</li>
        <li>WARNING : All detected N+1 queries</li>
        <li>ERROR   : All detected errors</li>
   </ul>
   <p>Logging can be enabled or disabled by the developer in Offcanvas Menu Settings.</p>
</p>

<p>This application has a built-in <b>User management & logging</b>, available to the developer :
    <ul>
        <li>User statistics by country of origin</li>
        <li>User statistics by year, month, week or day</li>
   </ul>
</p>

<p>
    The following activities are logged in the database:
    <ul>
        <li>create, update, delete on <b>persons (including Metadata)</b> and <b>couples</b></li>
        <li>create, update, delete on <b>teams</b></li>
        <li>create, update, delete, invite, remove on <b>users (Team members)</b></li>
    </ul>
</p>

<p>
    Activity loggings are available in Offcanvas Menu :
    <ul>
        <li>Persons (with Couples) in <b>People logbook</b></li>
        <li>Teams (with Users) in <b>Team logbook</b></li>
    </ul>
</p>

<p>This application has a built-in <b>Password Generator</b> to help users build secure passwords.</p>

### GEDCOM Import and Export

At present, <a href="https://gedcom.io/" target="_blank">GEDCOM</a> Import and Export functionality is under active development.<br/>
While still incomplete, initial Import and Export capabilities are already available.<br/>
The implementation of full GEDCOM (v7.x.x) Import and Export support represents a significantly larger effort than the development of the application itself.<br/>
To date, version 7 has not been fully implemented in any open-source php based project.<br/>
**It is strongly recommended to create a backup of your database before testing any Import or Export features on production data.**

## Languages

<ul>
    <li>German (DE)</li>
    <li>English (EN)</li>
    <li>Spanish (ES)</li>
    <li>French (FR)</li>
    <li>Hindi (HI)</li>
    <li>Indonesian (ID)</li>
    <li>Dutch (NL)</li>
    <li>Portuguese (PT)</li>
    <li>Turkish (TR)</li>
    <li>Vietnamese (VI)</li>
    <li>Simplified Chinese (ZH_CN)</li>
</ul>

Translations can be added by submitting a <b>Pull Request</b> to the project.<br />
Translation integrity can be checked by issuing the command `php artisan translations:check --excludedDirectories=vendor`

Instructions on how to add a language can be found in <a href="https://github.com/MGeurts/genealogy/blob/main/README-LANGUAGES.md" target="_blank">README-LANGUAGES.md<br/>

The applocation does **not support Right To Left (RTL) languages** like Arabic, Hebrew, Persian, Urdu, Pashto, Kurdish (Sorani), Uyghur, Syriac, Thaana, North Korean.</a>

## Uploads

Instructions on how to configure file and image uploads can be found in <a href="https://github.com/MGeurts/genealogy/blob/main/README-UPLOADS.md" target="_blank">README-UPLOADS.md</a>.

## Techniques

Both the <b>ancestors</b> and <b>descendants</b> family trees are build using <a href="https://dev.mysql.com/blog-archive/mysql-8-0-labs-recursive-common-table-expressions-in-mysql-ctes" target="_blank">Recursive Common Table Expressions</a> (Recursive CTE). This prevents the N+1 query problem generating the recursive tree family elements and dramatically improves performance.

## Installation

create a new project folder, cd into the folder

`git clone https://github.com/MGeurts/genealogy.git .`

`cp .env.example .env`

make the needed changes regarding name, url, database connection & mail server

`composer install`

`php artisan key:generate`

`php artisan storage:link`

`php artisan migrate:fresh --seed`

`npm install & npm run build`

`php artisan serve` or `npm run dev`

## Updating

Update instructions can be found in <a href="https://github.com/MGeurts/genealogy/blob/main/README-UPDATE.md" target="_blank">README-UPDATE.md</a>.

## Reporting security issues

Instructions on how to report security issues can be found in <a href="https://github.com/MGeurts/genealogy/blob/main/SECURITY.md" target="_blank">SECURITY.md</a>.

## Testing

Testing is done using <a href="https://pestphp.com/">Pest</a>.<br/>

Command: `php artisan test` or `./vendor/bin/pest`<br/>

<b>Production (or local development) data</b> should be stored in a MySQL or MariaDB database configured in `.env`.<br /><br />
<b>Testing data</b> should be stored in a separate MySQL or MariaDB database configured in `.env.testing` to avoid interfering with the production or development data.

## Contributing

Feel free to submit <b>Issues</b> or <b>Pull Requests</b>, for bugs, suggestions or feature requests.

## Docker Support

The application can be run in a Dockerized environment. Instructions can be found in <a href="https://github.com/MGeurts/genealogy/blob/main/README-DOCKER.md" target="_blank">README-DOCKER.md</a>.

## Documentation

The documentation is included in the applications help.
Visit the <a href="https://genealogy.kreaweb.be/help" target="_blank">demo project</a> to read the documentation.

## 💖 Support This Project

Maintaining this project takes time and effort. If you find it useful, consider supporting me:

-   [![Star on GitHub](https://img.shields.io/github/stars/MGeurts/genealogy?style=social)](https://github.com/MGeurts/genealogy)
-   [![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.me/MGeurtsKREAWEB)
-   [![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-orange?logo=buy-me-a-coffee)](https://buymeacoffee.com/MGeurts)

> Your support helps me improve and maintain [Genealogy](https://github.com/MGeurts/genealogy) and other open-source tools. Every bit is appreciated. Thank you! 🙏

## Impressum

### Design & Development (2024)

This Laravel application is designed and developed by <a href="https://www.kreaweb.be" target="_blank">kreaweb.be</a>.
