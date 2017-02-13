<?php
namespace Core\Response;

abstract class AbstractResponse
{
    /**
     * @var string
     */
    protected $content;

    public function __construct($content = [])
    {
        $this->setContent($content);
    }


    abstract public function render();

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }



}