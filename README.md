# DLM genealogy

![](https://img.shields.io/badge/PHP-8.3-informational?style=flat&logo=php&color=4f5b93)
![](https://img.shields.io/badge/Laravel-12-informational?style=flat&logo=laravel&color=ef3b2d)
![](https://img.shields.io/badge/Alpine.js-3-informational?style=flat&logo=Alpine.js&color=8BC0D0)
![](https://img.shields.io/badge/Livewire-3.6-informational?style=flat&logo=Livewire&color=fb70a9)
![](https://img.shields.io/badge/Filament-4.0-informational?style=flat&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgeG1sbnM6dj0iaHR0cHM6Ly92ZWN0YS5pby9uYW5vIj48cGF0aCBkPSJNMCAwaDQ4djQ4SDBWMHoiIGZpbGw9IiNmNGIyNWUiLz48cGF0aCBkPSJNMjggN2wtMSA2LTMuNDM3LjgxM0wyMCAxNWwtMSAzaDZ2NWgtN2wtMyAxOEg4Yy41MTUtNS44NTMgMS40NTQtMTEuMzMgMy0xN0g4di01bDUtMSAuMjUtMy4yNUMxNCAxMSAxNCAxMSAxNS40MzggOC41NjMgMTkuNDI5IDYuMTI4IDIzLjQ0MiA2LjY4NyAyOCA3eiIgZmlsbD0iIzI4MjQxZSIvPjxwYXRoIGQ9Ik0zMCAxOGg0YzIuMjMzIDUuMzM0IDIuMjMzIDUuMzM0IDEuMTI1IDguNUwzNCAyOWMtLjE2OCAzLjIwOS0uMTY4IDMuMjA5IDAgNmwtMiAxIDEgM2gtNXYyaC0yYy44NzUtNy42MjUuODc1LTcuNjI1IDItMTFoMnYtMmgtMnYtMmwyLTF2LTQtM3oiIGZpbGw9IiMyYTIwMTIiLz48cGF0aCBkPSJNMzUuNTYzIDYuODEzQzM4IDcgMzggNyAzOSA4Yy4xODggMi40MzguMTg4IDIuNDM4IDAgNWwtMiAyYy0yLjYyNS0uMzc1LTIuNjI1LS4zNzUtNS0xLS42MjUtMi4zNzUtLjYyNS0yLjM3NS0xLTUgMi0yIDItMiA0LjU2My0yLjE4N3oiIGZpbGw9IiM0MDM5MzEiLz48cGF0aCBkPSJNMzAgMThoNGMyLjA1NSA1LjMxOSAyLjA1NSA1LjMxOSAxLjgxMyA4LjMxM0wzNSAyOGwtMyAxdi0ybC00IDF2LTJsMi0xdi00LTN6IiBmaWxsPSIjMzEyODFlIi8+PHBhdGggZD0iTTI5IDI3aDN2MmgydjJoLTJ2MmwtNC0xdi0yaDJsLTEtM3oiIGZpbGw9IiMxNTEzMTAiLz48cGF0aCBkPSJNMzAgMThoNHYzaC0ydjJsLTMgMSAxLTZ6IiBmaWxsPSIjNjA0YjMyIi8+PC9zdmc+&&color=fdae4b&link=https://filamentphp.com)

## About this project

<b>DLM genealogy</b> is a family tree PHP application to record family members and their relationships, built with Laravel 12.

This <b>TallStack</b> application is built using:

<ul>
    <li>Laravel 12</li>
    <li>Laravel Jetstream 5 (featuring Teams)</li>
    <li>Livewire 4</li>
    <li>Alpine.js 3</li>
    <li>Tailwind CSS 4</li>
    <li>TallStackUI 2 (featuring Tabler Icons)</li>
    <li>Laravel Filament 4 (only Table Builder)</li>
</ul>

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
        At least PHP 8.3, supporting Laravel 12.<br/>
    </li>
    <li>
        At least MySQL 8.0.1 or MariaDB 10.2.2 or an equivalent database, supporting Recursive Common Table Expressions.
    </li>
</ul>

### License

This project is open-sourced software licensed under the [MIT license](LICENSE).

<p>This application has 2 family trees implemented, <b>BRITISH ROYALS</b> and <b>KENNEDY</b>.</p>

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
        <li>Backups can be scheduled by issuing a cron job on your development or production server</li>
        <li>An e-mail will be sent after each backup</li>
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

At present, GEDCOM Import and Export functionality is under active development.<br/>
While still incomplete, initial Import and Export capabilities are already available.<br/>
The implementation of full GEDCOM (v7.x.x) Import and Export support represents a significantly larger effort than the development of the application itself.<br/>
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

The application does **not support Right To Left (RTL) languages** like Arabic, Hebrew, Persian, Urdu, Pashto, Kurdish (Sorani), Uyghur, Syriac, Thaana, North Korean.

## Techniques

Both the <b>ancestors</b> and <b>descendants</b> family trees are built using Recursive Common Table Expressions (Recursive CTE). This prevents the N+1 query problem generating the recursive tree family elements and dramatically improves performance.

## Installation

create a new project folder, cd into the folder

`cp .env.example .env`

make the needed changes regarding name, url, database connection & mail server

`composer install`

`php artisan key:generate`

`php artisan storage:link`

`php artisan migrate:fresh --seed`

`npm install & npm run build`

`php artisan serve` or `npm run dev`

## Testing

Testing is done using Pest.<br/>

Command: `php artisan test` or `./vendor/bin/pest`<br/>

<b>Production (or local development) data</b> should be stored in a MySQL or MariaDB database configured in `.env`.<br /><br />
<b>Testing data</b> should be stored in a separate MySQL or MariaDB database configured in `.env.testing` to avoid interfering with the production or development data.
