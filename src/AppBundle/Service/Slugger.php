<?php

namespace AppBundle\Service;

class Slugger
{
    public function slugify($string)
    {
        $slug = preg_replace( '/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string))) );

        return $slug;
    }
}
