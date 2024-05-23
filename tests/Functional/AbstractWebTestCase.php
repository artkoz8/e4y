<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebTestCase extends WebTestCase
{
    public function setUp(): void
    {
        shell_exec('/var/www/e4y/bin/console doctrine:schema:drop -f --env=test');
        shell_exec('/var/www/e4y/bin/console doctrine:schema:update -f --env=test');
        shell_exec('/var/www/e4y/bin/console doctrine:fixtures:load -n --env=test --group=test');
    }
}