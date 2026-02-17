<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
    }

    public function testStartNormalGame(): void
    {
        $this->client->request('GET', '/game/');
        $this->client->submitForm('Mode Normal');

        $this->assertResponseRedirects('/game/play');
    }

    public function testGuessLetterPostRequest(): void
    {
        // Créer une partie
        $this->client->request('GET', '/game/');
        $this->client->submitForm('Mode Normal');

        // Proposer une lettre
        $this->client->request('POST', '/game/guess', ['letter' => 'r']);

        $this->assertResponseRedirects('/game/play');
    }

    public function testPlayPageDisplaysGame(): void
    {
        // Créer une partie
        $this->client->request('GET', '/game/');
        $this->client->submitForm('Mode Normal');

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.word');
    }
}
