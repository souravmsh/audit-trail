<?php

namespace Souravmsh\AuditTrail\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    public $signature = 'audit-trail:install';

    public $description = 'Install the audit trail package by running migrations and publishing the config, views, routes, and assets';

    public function handle(): int
    {
        $this->info('Starting the installation process for audit-trail...');

        // Ask and publish the config file for audit-trail
        $this->info('Publishing the config file...');
        Artisan::call('vendor:publish', [
            '--provider' => 'Souravmsh\AuditTrail\AuditTrailServiceProvider',
            '--tag' => 'audit-trail-config',
        ]);
        $this->comment('Config file published.');

        // Ask and publish the migration file for audit-trail
        if ($this->confirm('Do you want to publish the migration file?')) {
            $this->info('Publishing the migration file...');
            Artisan::call('vendor:publish', [
                '--provider' => 'Souravmsh\AuditTrail\AuditTrailServiceProvider',
                '--tag' => 'audit-trail-migrations',
            ]);
            $this->comment('Migration file published.');
        }
        // Run the migrations
        $this->info('Running migrations...');
        Artisan::call('migrate', [
            '--force' => true,
        ]);
        $this->comment('Migrations completed.');


        // Ask and publish the views for audit-trail
        if ($this->confirm('Do you want to publish the views?')) {
            $this->info('Publishing the views...');
            Artisan::call('vendor:publish', [
                '--provider' => 'Souravmsh\AuditTrail\AuditTrailServiceProvider',
                '--tag' => 'audit-trail-views',
            ]);
            $this->comment('Views published.');
        }

        // Ask and publish the assets for audit-trail
        if ($this->confirm('Do you want to publish the assets?')) {
            $this->info('Publishing the assets...');
            Artisan::call('vendor:publish', [
                '--provider' => 'Souravmsh\AuditTrail\AuditTrailServiceProvider',
                '--tag' => 'audit-trail-assets',
            ]);
            $this->comment('Assets published.');
        }

        $this->comment('Audit Trail setup is complete.');

        return self::SUCCESS;
    }
}
