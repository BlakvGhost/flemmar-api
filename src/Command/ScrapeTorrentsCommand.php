<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'app:scrape-torrents',
    description: 'Scrape les torrents depuis torrent9.lat',
)]
class ScrapeTorrentsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://www.torrent9.lat/category/films');
        $html = $response->getContent();

        $crawler = new Crawler($html);
        $crawler->filter('tr')->each(function (Crawler $tr) use ($output) {
            // vérification si le tr contient un lien vers une page detail
            $linkNode = $tr->filter('a[href^="/detail/"]');
            if ($linkNode->count() === 0) {
                return;
            }

            $href = $linkNode->attr('href');
            $title = trim($linkNode->attr('title') ?? $linkNode->text());

            $output->writeln("Film détecté : $title => $href");
            $this->processTorrent($href, $title, $output);
        });

        return Command::SUCCESS;
    }

    private function processTorrent(string $relativeUrl, string $title, OutputInterface $output): void
    {
        $client = HttpClient::create();
        $url = 'https://www.torrent9.lat' . $relativeUrl;
        $output->writeln("Scraping détail : $title => $url");

        try {
            $page = $client->request('GET', $url)->getContent();
            $crawler = new Crawler($page);

            // Magnet link
            $magnet = null;
            if (preg_match('/magnet:\?xt=urn:btih:[^\s"\']+/', $page, $matches)) {
                $magnet = $matches[0];
                $output->writeln("<info>Magnet trouvé : $magnet</info>");
            } else {
                $output->writeln("<error>Magnet non trouvé</error>");
            }

            // Extraction des informations détaillées
            $infoBlock = $crawler->filter('.movie-information');

            // Récupérer Seed et Leech
            $seed = $infoBlock->filter('ul')->eq(0)->filter('li')->eq(2)->text();  // 277
            $leech = $infoBlock->filter('ul')->eq(0)->filter('li')->eq(6)->text(); // 117

            // Poids du torrent
            $size = $infoBlock->filter('ul')->eq(1)->filter('li')->eq(2)->text(); // 4.17 GB

            // Date d'ajout
            $dateAdded = $infoBlock->filter('ul')->eq(2)->filter('li')->eq(2)->text(); // 21/04/2025

            // Catégorie principale
            $category = $infoBlock->filter('ul')->eq(3)->filter('li a')->eq(0)->text(); // Films

            // Sous-catégorie
            $subCategory = $infoBlock->filter('ul')->eq(4)->filter('li a')->eq(0)->text(); // Action, Aventure

            // Nom du fichier (titre du fichier vidéo)
            $fileName = $infoBlock->filter('p')->eq(0)->text(); // Bangkok.Dangerous.1080p...

            // Description
            $description = $infoBlock->filter('p')->eq(1)->text();

            // Affichage
            $output->writeln("Seeders: $seed");
            $output->writeln("Leechers: $leech");
            $output->writeln("Poids: $size");
            $output->writeln("Date ajout: $dateAdded");
            $output->writeln("Catégorie: $category");
            $output->writeln("Sous-catégorie: $subCategory");
            $output->writeln("Fichier: $fileName");
            $output->writeln("Description: $description");

            // 💾 Ici tu pourrais stocker tout ça en BDD
            // Exemple : $torrent->setLeech($leech); etc.

        } catch (\Exception $e) {
            $output->writeln("<error>Erreur : {$e->getMessage()}</error>");
        }
    }
}
