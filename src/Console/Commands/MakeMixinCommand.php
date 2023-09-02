<?php

namespace Aybarsm\Laravel\Support\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\suggest;

#[AsCommand(name: 'make:mixin')]
class MakeMixinCommand extends GeneratorCommand
{
    protected $signature = 'make:mixin
    {name : Mixin class name. (required)}
    {--force : Create the class even if the mixin already exists. (optional)}';

    protected $description = 'Create a new mixin class.';

    protected $type = 'Mixin';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['What class name should be used for the mixin?', 'E.g. ArrMixin'],
        ];
    }

    public function handle(): ?bool
    {
        $result = parent::handle();

        if ($result !== null) {
            return $result;
        }

        $qualifiedName = $this->qualifyClass($this->getNameInput());

        if (! ($inConfig = in_array($qualifiedName, config('extended-support.mixins.load')))) {
            $this->components->warn(sprintf(
                'Please add [%s] class in [extended-support.php] config file [mixins.load] list to be loaded automatically.', $qualifiedName
            ));
        }

        if (! $inConfig && ! File::exists(config_path('extended-support.php'))) {
            $publishNow = confirm(
                label: 'Configuration file for Extended Support package has not published yet. Would you like to publish it now?',
                yes: 'Yes, publish now.',
                no: 'No, don\'t publish now. I will do it later.',
                hint: "Your new [{$qualifiedName}] mixin needs to be added to config in order to be loaded automatically."
            );

            if ($publishNow) {
                $this->call('vendor:publish', [
                    '--provider' => 'Aybarsm\Laravel\Support\ExtendedSupportServiceProvider',
                    '--tag' => 'config',
                ]);
            }
        }

        return null;
    }

    protected function buildClass($name): string
    {
        $macroables = App::getMacroables();

        $bind = suggest(
            label: 'Which macroable class would you like to use this mixin to bind?',
            options: fn (string $search) => strlen($search) > 0
                ? Arr::where($macroables, function ($val, $key) use ($search) {
                    return Str::contains($val, $search, true);
                }) : $macroables,
            placeholder: 'E.g. Array',
            required: true,
            validate: fn (string $class) => class_exists($class) ? null : "Class [{$class}] does not exist."
        );

        $stub = Command::stubNormalise($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        return str_replace('{{ bind_class }}', Str::wrapSafe(rtrim(trim($bind), '::class'), '\\', ''), $stub);
    }

    protected function getStub(): string
    {
        $base = 'stubs/mixin.stub';

        return File::firstExists(base_path($base), realpath(__DIR__."/../../../{$base}"));
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Mixins';
    }
}
