<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use Carbon\Carbon;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class CsvProductsRepository implements ProductsRepository
{
    private Reader $reader;

    public function __construct()
    {
        $this->reader = Reader::createFromPath(base_path() . 'storage/tasks.csv');
        $this->reader->setDelimiter(';');

    }

    public function getAll(array $filters = []): ProductsCollection
    {
        $collection = new ProductsCollection();
        $statement = Statement::create()
            ->orderBy(function (array $a, array $b) {
                $timeA = new Carbon($a[3]);
                $timeB = new Carbon($b[3]);

                return $timeA->eq($timeB) ? 0 : ($timeA->lessThan($timeB) ? 1 : -1);
            });
        if (isset($filters['title'])) {
            $statement = $statement->where(function (array $record) use ($filters) {
                return $record[1] === $filters['title'];
            });
        }
        if (isset($filters['status'])) {
            $statement = $statement->where(function (array $record) use ($filters) {
                return $record[2] === $filters['status'];
            });
        }
        foreach ($statement->process($this->reader) as $record) {
            $collection->add(new Product(
                $record[0],
                $record[1],
                $record[2],
                $record[3]
            ));
        }
        return $collection;
    }

    public function save(Product $product): void
    {
        $writer = Writer::createFromPath(base_path() . 'storage/tasks.csv', 'a+');
        $writer->setDelimiter(';');
        $writer->insertOne($product->toArray());
    }

    public function getOne(string $id): ?Product
    {
        $statement = Statement::create()
            ->where(function ($record) use ($id) {
                return $record[0] === $id;
            })->limit(1);
        $record = $statement->process($this->reader)->fetchOne();

        if (empty($record)) return null;
        return new Product(
            $record[0],
            $record[1],
            $record[2],
            $record[3],
        );
    }

    public function delete(Product $product): void
    {
        $products = $this->getAll();
        unset($products[$product->getName()]);

        $records = [];

        foreach ($products as $product) {
            /** @var Product $product */
            $records[] = $product->toArray();
        }

        $writer = Writer::createFromPath(base_path() . 'storage/tasks.csv', 'w');
        $writer->setDelimiter(';');
        $writer->insertAll($records);
    }
}