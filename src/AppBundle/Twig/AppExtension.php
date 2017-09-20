<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('label', array($this, 'labelFilter'), array('is_safe' => array('html'))),
        );
    }

    public function labelFilter($text, $color)
    {
        $html = '<span class="label label-'.$color.'">'.$text.'</span></li>';

        return $html;
    }
}
