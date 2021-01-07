<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class CurrentPasswordModel
{
    /**
     * @UserPassword
     */
    public string $currentPassword;
}
