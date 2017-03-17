<?php

namespace rustymulvaney\bulma\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulma:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Bulma';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        // Progress count
        $progressCount = 0;

        // Questions
        if ($this->confirm('Would you like to install auth scaffolding?')) {
            $authScaffold = true;
            $progressCount++;

            // Admin User
            $name = $this->ask('Enter a name for the admin user.');
            $email = $this->ask('Enter the admin user\'s email address');
            $password = $this->ask('Enter the admin user\'s password');
        } else {
            $authScaffold = false;
        }

        // Setup the output
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));

        // create a new progress bar (4 units + $progressCount)
        $progress = new ProgressBar($output, 4 + $progressCount);
        $progress->setFormat('Progress: [%bar%] %message%');
        $progress->setOverwrite(false);

        // Check dependencies (Bulma)
        $progress->setMessage('Installing dependencies');
        $progress->start();

        if ($this->filesystem->exists('node_modules/bulma/') === false) {
            $processBulma = new Process('npm install bulma --save-dev');
            $processBulma->setWorkingDirectory(base_path())->run();
        }

        if ($this->filesystem->exists('node_modules/font-awesome/') === false) {
            $processFontAwesome = new Process('npm install font-awesome --save-dev');
            $processFontAwesome->setWorkingDirectory(base_path())->run();
        }

        // Auth Scaffolding
        if ($authScaffold === true) {

            if ($this->filesystem->exists('vendor/laravel/passport') === false) {

                // Install Passport
                $process = new Process('composer require laravel/passport');
                $process->setWorkingDirectory(base_path())->run();
                $process->wait();

                // Add the service provider for passport
                $file = config_path().'/app.php';
                $search = 'Laravel\Tinker\TinkerServiceProvider::class,';
                $insert = 'Laravel\Passport\PassportServiceProvider::class,';
                $replace = $search."\n \t \t".$insert;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

                $process = new Process('/usr/local/bin/composer dump-autoload');
                $process->setWorkingDirectory(base_path())->run();
                $process->wait();
                $this->callSilent('clear-compiled');

                // Run the passport migrations and install passport
                $process = new Process('php artisan migrate');
                $process->setWorkingDirectory(base_path())->run();
                $process->wait();

                $process = new Process('php artisan passport:install');
                $process->setWorkingDirectory(base_path())->run();
                $process->wait();

                // Add HasApiTokens to the user model
                $file = app_path().'/User.php';
                $search = 'use Illuminate\Notifications\Notifiable;';
                $insert = 'use Laravel\Passport\HasApiTokens;';
                $replace = $insert."\n".$search;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

                $search = 'use Notifiable;';
                $replace = 'use HasApiTokens, Notifiable;';
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

                // Add Passport to AuthServiceProvider
                $file = app_path().'/Providers/AuthServiceProvider.php';
                $search = '$this->registerPolicies();';
                $insert = 'Passport::routes();';
                $replace = $search."\n \t \t".$insert;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

                $file = app_path().'/Providers/AuthServiceProvider.php';
                $search = 'use Illuminate\Support\Facades\Gate;';
                $insert = 'use Laravel\Passport\Passport;';
                $replace = $search."\n".$insert;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

                // Change api provider to passport in auth config file
                $file = config_path().'/auth.php';
                $search = '\'driver\' => \'token\',';
                $insert = '\'driver\' => \'passport\',';
                file_put_contents($file, str_replace($search, $insert, file_get_contents($file)));

                // Add CreateFreshApiToken to HTTP Kernel
                $file = app_path().'/Http/Kernel.php';
                $search = '\App\Http\Middleware\VerifyCsrfToken::class,';
                $insert = '\Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,';
                $replace = $search."\n \t \t \t".$insert;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));
            }

            // Generate auth scaffolding
            $progress->setMessage('Generating auth scaffolding');
            $progress->advance();
            $this->callSilent('make:auth');

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->save();
        }

        // Publish assets
        $progress->setMessage('Publishing the Bulma assets and config files');
        $progress->advance();
        $this->callSilent('vendor:publish', [
            '--provider' => 'rustymulvaney\bulma\BulmaServiceProvider',
            '--tag'      => 'install',
            '--force'    => true,
        ]);

        // Install Node modules
        $progress->setMessage('Installing Node modules');
        $progress->advance();
        $process = new Process('npm install');
        $process->setWorkingDirectory(base_path())->run();
        $process->wait();

        // Fix package.json reference to cross-env
        $progress->setMessage('Fix package.json reference to cross-env');
        $progress->advance();
        $file = base_path().'/package.json';
        $search = 'cross-env/bin';
        $replace = 'cross-env/dist/bin';
        file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

        // Run Laravel Mix
        $progress->setMessage('Running Laravel Mix');
        $progress->advance();
        $process = new Process('npm run dev');
        $process->setWorkingDirectory(base_path())->run();
        $process->wait();

        // End the progress bar
        $progress->finish();
    }
}
