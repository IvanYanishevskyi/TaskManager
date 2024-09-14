<?php
namespace App\Twig;

use Symfony\Component\HttpFoundation\Session\SessionFactory;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $session;

    public function __construct(SessionFactory $sessionFactory)
    {
        $this->session = $sessionFactory->createSession(); // Create session from factory
    }

    public function getGlobals(): array
    {
        return [
            'username' => $this->session->get('username','гость'),
        ];
    }
}