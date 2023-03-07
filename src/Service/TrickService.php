<?php

namespace App\Service;

use App\Controller\TrickController;

class TrickService extends TrickController
{

    public function userIsLogged()
    {
        if (!$this->getUser()) {
            return false;
        }

        return true;
    }

    public function userIsAdmin()
    {
        if (!$this->getUser()) {
            return false;
        }

        if ($this->getUser()->getRoles()[0] === 'ROLE_ADMIN') {
            return true;
        }

        return false;
    }

    public function userIsAuthor($trick)
    {
        if (!$this->getUser()) {
            return false;
        }

        if ($this->getUser()->getId() === $trick->getAuthor()->getId()) {
            return true;
        }

        return false;
    }

}
