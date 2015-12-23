<?php

namespace FOSUserExtendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FOSUserExtendBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
