<?php

namespace App\Jobs\GenerateCatalog;


class GenerateCatalogMainJob extends AbstractJob
{

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function handle()
    {
        $this->debug('start');

        //Сначала кешируем продукты
        GenerateCatalogCacheJob::dispatchNow();

        //Затем создаем цепочку заданий формирования файлов с ценами
        $chainPrices = $this->getChainPrices();

        // Основные подзадачи
        $chainMain = [
            new GenerateCategoriesJob, // Генерация категорий
            new GenerateDeliveriesJob, // Генерация способов доставок
            new GeneratePointsJob, // Генерация пунктов выдачи
        ];

        //Подзадачи которые должны выполнится самыми последними
        $chainLast = [
            // Архивирование файлов и перенос архива в публичную папку
            new ArchiveUploadsJob,
            // Отправка уведомления стороннему сервису о том что можно скачать новый файл каталога товаров
            new SendPriceRequestJob,
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        GenerateGoodsFileJob::withChain($chain)->dispatch();

        $this->debug('finish');
    }

    /**
     * Формирование цепочек подзадач по генерации файлов с ценами
     *
     * @return array
     */
    private function  getChainPrices()
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunckJob($chunk, $fileNum);
            $fileNum++;
        }

        return  $result;
    }
}
