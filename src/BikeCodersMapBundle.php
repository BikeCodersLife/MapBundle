<?php

namespace BikeCoders\MapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BikeCodersMapBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
