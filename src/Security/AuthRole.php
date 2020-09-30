<?php

namespace App\Security;

class AuthRole
{
    /**
     * zwykły, niezalogowany użytkownik
     * @var string
     */
    public const ROLE_QUEST = 'ROLE_QUEST';

    /**
     * użytkownik zalogowany jako klient
     * @var string
     */
    public const ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    /**
     * użytkownik odpowiedzialny za zarządzanie
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

} //end class