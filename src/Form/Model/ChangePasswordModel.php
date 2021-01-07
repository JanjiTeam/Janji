<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordModel
{
    /**
     * @UserPassword
     */
    public string $oldPassword;
    public string $plainPassword;
}
