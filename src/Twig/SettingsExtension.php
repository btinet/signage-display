<?php

namespace App\Twig;

use App\Service\InfoScreenSettings;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingsExtension extends AbstractExtension
{
    private InfoscreenSettings $settings;

    // Symfony injiziert den Service automatisch per Dependency Injection
    public function __construct(InfoScreenSettings $settings)
    {
        $this->settings = $settings;
    }

    public function getFunctions(): array
    {
        // Registriert die Funktion {{ setting('key') }} in Twig
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->settings->get($key, $default);
    }
}