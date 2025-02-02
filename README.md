# AuditTrail a Laravel package for managing audit logs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/souravmsh/compressed-output.svg?style=flat-square)](https://packagist.org/packages/souravmsh/compressed-output)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/souravmsh/compressed-output/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/souravmsh/compressed-output/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/souravmsh/compressed-output/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/souravmsh/compressed-output/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/souravmsh/compressed-output.svg?style=flat-square)](https://packagist.org/packages/souravmsh/compressed-output)


---
<b>Audit Trail</b> is a Laravel package that provides an easy-to-use mechanism for maintaining table row audit logs. It automatically tracks changes to database records, including created, updated, and deleted events, making it easier to monitor data modifications and user activities.

## Installation
You can install the package via Composer:

```bash
composer require souravmsh/audit-trail
```

Alternatively, you can install it manually:

```bash
mkdir -p packages/souravmsh
cd packages/souravmsh

git clone https://github.com/souravmsh/audit-trail.git

composer require souravmsh/audit-trail:dev-main

```
### Publish and Run Migrations

To publish and run the migrations, use:

```bash
php artisan audit-trail:install
```
This will create the necessary database tables for logging audit records.

## Usage
### Logging Audit Data

You can manually log audit records using the <b>AuditTrail</b> facade:

Log an audit trail entry using the AuditTrail package.

This example demonstrates how to log changes made to a specific model.
The audit log captures details such as the type of action performed, 
the model being updated, the changed data, and the user responsible.

Parameters:
- type: The action type (CREATED, UPDATED, DELETED, LOGGEDIN, IMPORTED, EXPORTED, OTHER).
- message: (Optional) message.
- model_type: The fully qualified class name of the model being logged.
- model_id: The specific model instance ID associated with the change.
- data: An array containing changes, specifying old and new values.

```php
use Souravmsh\AuditTrail\Facades\AuditTrail;

        
// Supports both array-based calls
AuditTrail::log([
    "type"       => "CREATED",
    "message"    => "A recond has created",
    "model_type" => "App\Models\User",
    "model_id"   => 1,
    "data"       => ["title" => ["old" => "John", "new" => "Alex"]],
]);

// Handles loggedin without a model
AuditTrail::log([
    "type"    => config('audit-trail.migration.type.loggedin'),
    "message" => "A user login in 1."
]);

// or
AuditTrail::log("LOGGEDIN", "A user logged in 2.");
```

### Retrieving Audit History
To fetch the audit log for a specific model:

```php
use Souravmsh\AuditTrail\Facades\AuditTrail;

$auditLogs = AuditTrail::history([
    "per_page"     => "10",
    "limit"        => "100",
    "model_type"   => "App\Models\User",
    "model_id"     => "1",
    "creator_type" => "",
    "creator_id"   => "",
    "show_model"   => "false",
    "show_creator" => "true"
]);
```

### Blade Component for Displaying Audit Logs:-
You can use the built-in Blade component widget to display audit logs in your UI:

Blade Component for Displaying Audit Logs

This component is used to display audit trail logs in the UI.
You can configure various parameters to filter and control the displayed logs.

Parameters:
- title: The title of the audit trail widget.
- per_page: Number of records displayed per page.
- limit: Maximum number of audit logs to fetch.
- model_type: The target model type for which logs are fetched.
- model_id: The specific model instance ID to filter logs.
- creator_type: The type of creator who performed the action (e.g., User, Admin).
- creator_id: The specific creator ID to filter logs.
- show_model: Set to "true" to load data with the 'model' relation, which links to the target table.
- show_creator: Set to "true" to load data with the 'creator' relation, which links to the user who performed the action.

```html
<x-audit-trail-widget 
    title="Audit Trail History"
    per_page="10"
    limit="100"
    model_type="App\\Models\\User"
    model_id="1"
    creator_type=""
    creator_id=""
    show_model="false" 
    show_creator="true" 
/>
```

### Adding Custom Styles
To ensure the audit trail widget looks great, include the custom CSS file in your Blade template. Add the following `<link>` tag to the `<head>` section of your template:

```html
<link href="{{ asset('vendor/souravmsh/audit-trail/style.css') }}" rel="stylesheet">
```

## Testing
Run tests using:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
