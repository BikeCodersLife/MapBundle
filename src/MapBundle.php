<?php

namespace BikeCodersLife\MapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MapBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
