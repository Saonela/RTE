<?php

namespace RTER\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RTERUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
