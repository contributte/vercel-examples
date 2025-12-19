<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if(isset($_ENV['NOW_REGION']) || isset($_ENV['VERCEL'])){
            return '/tmp/symfony/cache/'.$this->environment;
        }

        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        if(isset($_ENV['NOW_REGION']) || isset($_ENV['VERCEL'])){
            return '/tmp/symfony/log';
        }

        return $this->getProjectDir().'/var/log';
    }
}
