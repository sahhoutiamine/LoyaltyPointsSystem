<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader, [
            'cache' => false, // Set to 'cache' directory for production
            'debug' => true,
        ]);
        
        // Add session global to twig
        $this->twig->addGlobal('session', $_SESSION);
    }

    protected function render($view, $data = [])
    {
        echo $this->twig->render($view, $data);
    }
    
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
