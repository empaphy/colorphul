<?php

namespace Empaphy\Colorphul;

use Symfony\Bundle\FrameworkBundle as framework;
use Symfony\Component\HttpKernel as http_kernel;

class Kernel extends http_kernel\Kernel
{
    use framework\Kernel\MicroKernelTrait;
}
