<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Imports products from a CSV file or updates existing ones.',
)]
class ImportProductsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvPath = __DIR__ . '/../../data/products.csv';

        if (!file_exists($csvPath)) {
            $output->writeln('<error>CSV fájl nem található: ' . $csvPath . '</error>');
            return Command::FAILURE;
        }

        try {
            $csv = Reader::createFromPath($csvPath, 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            $imported = 0;
            $updated = 0;

            foreach ($records as $record) {
                // Ellenőrzés, létezik-e már a termék név alapján
                $existing = $this->entityManager
                    ->getRepository(Product::class)
                    ->findOneBy(['name' => $record['name']]);

                if ($existing) {
                    $updatedFlag = false;

                    // Frissítjük az image_url-t, ha szükséges
                    if (!empty($record['image_url']) && $existing->getImageUrl() !== $record['image_url']) {
                        $existing->setImageUrl($record['image_url']);
                        $updatedFlag = true;
                    }

                    // Frissítjük a description-t (fix Lorem Ipsum)
                    $newDescription = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
                    if ($existing->getDescription() !== $newDescription) {
                        $existing->setDescription($newDescription);
                        $updatedFlag = true;
                    }

                    // Frissítjük a category-t, ha szükséges
                    if (isset($record['category']) && $existing->getCategory() !== $record['category']) {
                        $existing->setCategory($record['category']);
                        $updatedFlag = true;
                    }

                    // Frissítjük a category_id-t, ha szükséges
                    if (isset($record['category_id']) && $existing->getCategoryId() !== $record['category_id']) {
                        $existing->setCategoryId($record['category_id']);
                        $updatedFlag = true;
                    }

                    if ($updatedFlag) {
                        $this->entityManager->persist($existing);
                        $updated++;
                        $output->writeln('<comment>Termék frissítve: ' . $record['name'] . '</comment>');
                    } else {
                        $output->writeln('<comment>Termék már létezik, kihagyva: ' . $record['name'] . '</comment>');
                    }
                    continue;
                }

                $product = new Product();
                $product->setName($record['name']);
                $product->setPrice((int)$record['price']);
                $product->setNetPrice((int)round($record['price'] / 1.27));
                $product->setImageUrl($record['image_url'] ?? '');
                $product->setCategory($record['category'] ?? 'N/A');
                $product->setCategoryId($record['category_id'] ?? 'N/A');

                $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');

                $identifier = strtoupper(substr(md5($record['name']), 0, 9));
                $product->setIdentifier($identifier);

                $this->entityManager->persist($product);
                $imported++;

                $output->writeln('<info>Új termék hozzáadva: ' . $record['name'] . '</info>');
            }

            $this->entityManager->flush();

            $output->writeln("<info>Import kész. Új termékek: {$imported}, frissített termékek: {$updated}.</info>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Hiba történt: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
