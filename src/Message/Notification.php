<?php
/**
 * Mensage de notificación
 */
namespace App\Message;

/**
 * Class Notification
 * @package App\Message
 */
class Notification
{
    /**
     * Contenido de notificación
     * @var string
     */
    private $content;

    /**
     * Notification constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Obtener contenido de la notificación
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Cambiar contenido de notificación
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }
}
