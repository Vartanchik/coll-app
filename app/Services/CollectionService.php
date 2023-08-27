<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * Service for collections manipulation.
 */
class CollectionService
{
    /**
     * Sort operators' => logical operators (mapping roles).
     *
     * @var string[]
     */
    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];

    /**
     * Get collections by filters from database.
     *
     * @param array|null $remainingAmount
     * @param bool $isLessThanTargetAmount
     * @return Collection
     */
    public function getCollectionsWithFilters(?array $remainingAmount, bool $isLessThanTargetAmount = false): Collection
    {
        $amountsSubQuery = DB::table('contributors')
            ->selectRaw('collection_id, SUM(amount) as total_amount')
            ->groupBy('collection_id');

        $collectionsQuery = DB::table('collections')
            ->leftJoinSub(
                $amountsSubQuery,
                'amounts',
                'collections.id',
                '=',
                'amounts.collection_id'
            );

        // adds condition to query where contributors' amounts sum are less than the target amount
        if ($isLessThanTargetAmount) {
            $collectionsQuery->where(function (Builder $q) {
                $q->whereColumn('total_amount', '<', 'target_amount')
                    ->orWhereNull('total_amount');
            });
        }

        // adds condition to query about remaining amount
        if ($remainingAmount !== null) {
            $remainingAmountSortOperator = array_key_first($remainingAmount);
            $remainingAmountValue = $remainingAmount[$remainingAmountSortOperator];

            $collectionsQuery->whereRaw(
                "target_amount - total_amount {$this->operatorMap[$remainingAmountSortOperator]} ?",
                $remainingAmountValue
            );
        }

        return $collectionsQuery->get();
    }
}
