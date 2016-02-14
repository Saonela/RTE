<?php

namespace RTE\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RTEUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
