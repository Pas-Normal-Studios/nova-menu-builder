<?php

namespace OptimistDigital\MenuBuilder\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Models\ShopifyStore;

class SyncMenu extends Action
{
    use InteractsWithQueue, Queueable;


    
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $shopifyStores = ShopifyStore::whereIsPos(false)->get();

        $shopifyStores->each(function($shopifyStore) {
            $j = new SyncPagesToShopifyJob($shopifyStore);
            dispatch($j)->onQueue($shopifyStore->queue);
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    public function name()
    {
    return 'Sync menu to Shopify';
    }
}
