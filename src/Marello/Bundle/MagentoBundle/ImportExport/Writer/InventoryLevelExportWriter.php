<?php
/**
 * Created by PhpStorm.
 * User: muhsin
 * Date: 11/05/2018
 * Time: 14:12
 */

namespace Marello\Bundle\MagentoBundle\ImportExport\Writer;

class InventoryLevelExportWriter extends AbstractExportWriter
{
    const CONTEXT_POST_PROCESS_KEY = 'postProcessInventoryExport';
    const PRODUCT_SKU = 'sku';

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $item = reset($items);

        if (!$item) {
            $this->logger->error('Wrong inventory data', (array)$item);

            return;
        }

        $this->transport->init($this->getChannel()->getTransport());

        $this->writeExistingItem($item);
    }

    /**
     * @param array $item
     */
    protected function writeExistingItem(array $item)
    {
        try {
            $productId = $item['productId'];
            $result = $this->transport->updateStock($item);

            if ($result) {
                $this->stepExecution->getJobExecution()
                    ->getExecutionContext()
                    ->put(self::CONTEXT_POST_PROCESS_KEY, [$item]);

                $this->logger->info(
                    sprintf(
                        'Product with id %s and data %s successfully updated',
                        $productId,
                        json_encode($item)
                    )
                );
            } else {
                $this->logger->error(
                    sprintf(
                        'Product with id %s and data %s was not updated',
                        $productId,
                        json_encode($item)
                    )
                );
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->stepExecution->addFailureException($e);
        }
    }
}
