<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\GameEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/game', name:'app_game_')]
class GameController extends AbstractController
{
    public function __construct(
        private GameEngine $gameEngine,
    ){}

    #[Route('/', name: "index", methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('game/select.html.twig', [
            'games' => $this->gameEngine->getInProgressGames(),
        ]);
    }

    #[Route('/resume/{id}', name: "resume", methods: ['GET'])]
    public function resume(string $id, Request $request): Response
    {
        $uuid = Uuid::fromString($id);
        $game = $this->gameEngine->loadGame($uuid);

        if ($game === null) {
            return $this->redirectToRoute('app_game_index');
        }

        $request->getSession()->set('game_id', (string) $game->getId());

        return $this->redirectToRoute('app_game_play');
    }

    #[Route('/start', name: "start", methods: ['POST'])]
    public function start(Request $request): Response
    {
        $mode = $request->request->getString('mode');
        $game = $this->gameEngine->createGame($mode);

        $request->getSession()->set('game_id', (string) $game->getId());

        return $this->redirectToRoute('app_game_play');
    }

    #[Route('/play', name: "play", methods: ['GET'])]
    public function play(Request $request): Response
    {
        $game = $this->loadGameFromSession($request);

        if ($game === null) {
            return $this->redirectToRoute('app_game_index');
        }

        return $this->render("game/index.html.twig", [
            'game' => $game,
        ]);
    }

    #[Route('/guess', name: "guess", methods: ['POST'])]
    public function guess(Request $request): Response
    {
        $game = $this->loadGameFromSession($request);

        if ($game === null) {
            return $this->redirectToRoute('app_game_index');
        }

        $letter = $request->request->getString('letter');

        if ($letter !== '') {
            $this->gameEngine->guess($game, $letter);
        }

        return $this->redirectToRoute('app_game_play');
    }

    #[Route('/hint', name: "hint", methods: ['POST'])]
    public function hint(Request $request): Response
    {
        $game = $this->loadGameFromSession($request);

        if ($game === null) {
            return $this->redirectToRoute('app_game_index');
        }

        $this->gameEngine->useHint($game);

        return $this->redirectToRoute('app_game_play');
    }

    private function loadGameFromSession(Request $request): ?\App\Domain\Interface\GameInterface
    {
        $gameId = $request->getSession()->get('game_id');

        if ($gameId === null) {
            return null;
        }

        return $this->gameEngine->loadGame(Uuid::fromString($gameId));
    }
}
