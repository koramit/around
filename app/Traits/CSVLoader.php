<?php

namespace App\Traits;

trait CSVLoader
{
    public function seed(string $path, string $className): void
    {
        $items = $this->loadCSV($path);
        $now = now();
        $items = array_map(function ($item) use ($now) {
            unset($item['id']);
            $item['created_at'] = $now;
            $item['updated_at'] = $now;

            return $item;
        }, $items);

        $className::insert($items);
    }

    protected function loadCSV(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }
        $items = array_map('str_getcsv', file($path));
        array_walk($items, function (&$item) use ($items) {
            $item = array_combine($items[0], $item);
        });
        array_shift($items);

        return $items;
    }
}
