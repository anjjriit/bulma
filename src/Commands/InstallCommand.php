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
    protected $description = 'Install Bulma Scaffolding';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
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
            $password = $this->secret('Enter the admin user\'s password');
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

        // Install Node modules
        $progress->setMessage('Installing Node modules');
        $progress->advance();
        $this->runProcess('npm install', 300);

        // Check dependencies (Bulma)
        $progress->setMessage('Installing dependencies');
        $progress->start();

        if ($this->checkPathExists('node_modules/bulma/') === false) {
            $this->runProcess('npm install bulma --save-dev');
        }

        if ($this->checkPathExists('node_modules/font-awesome/') === false) {
            $this->runProcess('npm install font-awesome --save-dev');
        }

        // Auth Scaffolding
        if ($authScaffold === true) {

            // Generate auth scaffolding
            $progress->setMessage('Generating auth scaffolding');
            $progress->advance();
            $this->callSilent('make:auth');

            if ($this->filesystem->exists('vendor/laravel/passport') === false) {

                // Install Passport
                $this->runProcess('composer require laravel/passport', 300);

                // Add the service provider for passport
                $file = config_path().'/app.php';
                $search = 'Laravel\Tinker\TinkerServiceProvider::class,';
                $insert = 'Laravel\Passport\PassportServiceProvider::class,';
                $replace = $search."\n \t \t".$insert;
                file_put_contents($file, str_replace($search, $replace, file_get_contents($file)));

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

                // Run the passport migrations and install passport
                $this->runProcess('php artisan migrate');
                $this->runProcess('php artisan passport:install');
            }

            // Create admin user
            if (isset($name, $email, $password)) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->password = bcrypt($password);
                $user->save();
            }
        }

        // Publish assets
        $progress->setMessage('Publishing the Bulma assets and config files');
        $progress->advance();
        $this->callSilent('vendor:publish', [
            '--provider' => 'rustymulvaney\bulma\BulmaServiceProvider',
            '--tag'      => 'install',
            '--force'    => true,
        ]);

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
        $this->runProcess('npm run dev');

        // End the progress bar
        $progress->finish();
    }

    private function checkPathExists($path)
    {
        return $this->filesystem->exists($path);
    }

    private function runProcess($command, $timeout = null)
    {
        $process = new Process($command);
        if ($timeout != null) {
            $process->setTimeout($timeout);
        }
        $process->setWorkingDirectory(base_path())->start();
        $process->wait();

        return $process->getExitCode();
    }
}
