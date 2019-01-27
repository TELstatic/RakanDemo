<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class ProductItem extends Model
{
    const table = 'product_items';

    protected $guarded = [];

    // 重写更新为 批量更新
    public static function updateBatch(array $attributes = [])
    {
        try {
            $tableName = self::table;
            $firstRow = current($attributes);
            $columns = array_keys($firstRow);

            $referenceColumn = isset($firstRow['id']) ? 'id' : current($columns);
            unset($columns[0]);
            // 拼接sql语句
            $updateSql = "UPDATE ".$tableName." SET ";
            $sets = [];
            $bindings = [];
            foreach ($columns as $column) {
                $setSql = "`".$column."` = CASE ";
                foreach ($attributes as $data) {
                    $setSql .= "WHEN `".$referenceColumn."` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$column];
                }
                $setSql .= "ELSE `".$column."` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn = collect($attributes)->pluck($referenceColumn)->values()->all();
            $bindings = array_merge($bindings, $whereIn);
            $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ")." WHERE `".$referenceColumn."` IN (".$whereIn.")";

            return DB::update($updateSql, $bindings);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 减去库存
     */
    public function decrementStock($amount)
    {
        if ($amount < 0) {
            throw new InternalErrorException('减库存不可小于0');
        }

        return $this->newQuery()->where('id', $this->id)->where('reserve', '>=', $amount)->decrement('reserve', $amount);
    }

    /**
     * 恢复库存
     */
    public function incrementStock($amount)
    {
        if ($amount < 0) {
            throw new InternalErrorException('加库存不可小于0');
        }
 
        return $this->increment('reserve', $amount);
    }

}
