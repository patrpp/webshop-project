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
    description: 'Imports products from a CSV file.',
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
            $csv->setHeaderOffset(0); // első sor a fejléc
            $records = $csv->getRecords();

            $imported = 0;
            foreach ($records as $record) {
                // Duplikáció ellenőrzés név alapján
                $existing = $this->entityManager
                    ->getRepository(Product::class)
                    ->findOneBy(['name' => $record['name']]);

                if ($existing) {
                    $output->writeln('<comment>Termék már létezik, kihagyva: ' . $record['name'] . '</comment>');
                    continue;
                }

                $product = new Product();
                $product->setName($record['name']);
                $product->setPrice((int)$record['price']);
                $product->setNetPrice((int)round($record['price'] / 1.27)); // ÁFA kiszámítása, ha 27%
                $product->setImageUrl($record['image_url'] ?? '');

                // Lorem ipsum szöveg hozzáadása fiktív leírásként (nincs description meződ, szóval pl. kategóriában használható)
                $product->setCategory('Lorem ipsum category');
                $product->setCategoryId('CATEG_' . random_int(1000, 9999));

                // Egyedi identifier (pl. hash alapú)
                $identifier = strtoupper(substr(md5($record['name']), 0, 9));
                $product->setIdentifier($identifier);

                $this->entityManager->persist($product);
                $imported++;
            }

            $this->entityManager->flush();
            $output->writeln("<info>Sikeres import: {$imported} termék.</info>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Hiba történt: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
