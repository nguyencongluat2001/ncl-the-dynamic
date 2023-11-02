<?php

namespace Modules\Core\Efy\FormDynamic\listForm;

use Modules\Core\Efy\FunctionHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Modules\Core\Efy\Http\Debug\ApiDebug;

class QueryBuilder
{

    public $currentPage = 1;
    public $perPage = 1000;

    private $sqlConfig;
    private $filters;

    private $query;

    public $displayColumn;

    /**
     * Khởi tạo các giá trị ban đầu
     * 
     * @param object $sqlConfig Đối tượng cấu hình sql
     * @param array $filters Dữ liệu từ client
     * @return QueryBuilder
     */
    public function init(object $sqlConfig, array $filters): QueryBuilder
    {
        $this->sqlConfig = $sqlConfig;
        $this->filters = $filters;

        if (isset($filters['page'])) $this->currentPage = $filters['page'];
        if (isset($filters['per_page'])) $this->perPage = $filters['per_page'];

        $this->genQueryTable()
            ->genJoinTable()
            ->genSelectColumn()
            ->genGroupCondition()
            ->genOrderBy();

        return $this;
    }

    /**
     * Tạo query ban đầu
     * 
     * @return QueryBuilder
     */
    private function genQueryTable(): QueryBuilder
    {
        if (!$this->sqlConfig->primary_table || $this->sqlConfig->primary_table === '') {
            return false;
        }
        $primary_table = $this->sqlConfig->primary_table;
        $this->query = DB::table($primary_table);

        return $this;
    }

    /**
     * Tạo lệnh join vào query
     * 
     * @return QueryBuilder
     */
    private function genJoinTable(): QueryBuilder
    {
        if (!$this->sqlConfig->join_table || count($this->sqlConfig->join_table) === 0) return $this;
        foreach ($this->sqlConfig->join_table as $joinTables) {
            $first_table = (string)$joinTables->first_table;
            $first_column = (string)$joinTables->first_column;
            $second_table = (string)$joinTables->second_table;
            $second_column = (string)$joinTables->second_column;
            $rule = (string)$joinTables->rule;
            $this->query->{$rule}($second_table, "$first_table.$first_column", '=', "$second_table.$second_column");
        }

        return $this;
    }

    /**
     * Tạo lệnh select vào query
     * 
     * @return QueryBuilder
     */
    private function genSelectColumn(): QueryBuilder
    {
        if (!$this->sqlConfig->display_column || count($this->sqlConfig->display_column) === 0) return $this;
        $arrSelect = array();
        $arrConvert = array();
        $i = 0;
        $j = 0;
        foreach ($this->sqlConfig->display_column as $columns) {
            $table_name   = (string)$columns->table_name;
            $column_name  = (string)$columns->column_name;
            // $xml_data     = (string)$columns->xml_data;
            $alias_name   = (string)$columns->alias_name;
            $phpfunction  = (string)$columns->phpfunction;

            $selectColumn = "$table_name.$column_name";
            if ($alias_name !== '' && $column_name !== "*") {
                $selectColumn = "$selectColumn AS $alias_name";
            }
            if ($phpfunction !== '') {
                $arrConvert[$j]['phpfunction'] = $phpfunction;
                $arrConvert[$j]['alias_name'] = $alias_name;
                $j++;
            }
            $arrSelect[$i] = $selectColumn;
            $i++;
        }
        $this->displayColumn = $arrConvert;
        $this->query->select($arrSelect);

        return $this;
    }

    /**
     * Tạo các nhóm điều kiện where vào query
     * 
     * @return QueryBuilder
     */
    private function genGroupCondition(): QueryBuilder
    {
        foreach ($this->sqlConfig->condition as $groups) {
            $this->query->where(function ($query) use ($groups) {
                return $this->genCondition($query, $groups);
            });
        }

        return $this;
    }

    /**
     * Tạo từng điều kiện trong nhóm điều kiện
     * 
     * @param object $query
     * @param object $groupCondition Nhóm điều kiện
     * @return object
     */
    private function genCondition(object $query, array $groupCondition): object
    {
        foreach ($groupCondition as $condition) {
            $table_name  = (string)$condition->table_name;
            $column_name = (string)$condition->column_name;
            $clause      = (string)$condition->clause;
            $compare     = (string)$condition->compare;
            $value       = (string)($condition->value ?? '');
            $param       = (string)($condition->param ?? '');

            if ($param !== "") {
                if (isset($this->filters[$param])) {
                    $value = $this->filters[$param];
                }
            }
            if ($value !== '') {
                if (strtolower($compare) === "like") {
                    $query->{$clause}("$table_name.$column_name", $compare, "%$value%");
                    // } elseif ($compare == "object_name") {
                    //     $query->orWhereRaw("convert(nvarchar(max)," . $table_name . ".xml_data.query('/root/data_list/object_name/text()')) like N'%" . $value . "%'");
                    // } elseif ($compare == "contains") {
                    //     $text = \str_replace(",", " ", trim($value));
                    //     $text = \str_replace(" ", " AND ", trim($text));
                    //     $query->orWhereRaw("CONTAINS($table_name.$column_name,'$text')");
                } else if (strtolower($compare) === 'in') {
                    $arrValue = explode(',', $value);
                    $query->whereIn("$table_name.$column_name", $arrValue);
                } else {
                    if ($compare !== "") {
                        $query->{$clause}("$table_name.$column_name", $compare, $value);
                    } else {
                        $query->{$clause}("$table_name.$column_name", $value);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Tạo kệnh order by vào query
     * 
     * @return QueryBuilder
     */
    private function genOrderBy(): QueryBuilder
    {
        foreach ($this->sqlConfig->order as $order) {
            $table_name  = (string)$order->table_name;
            $column_name = (string)$order->column_name;
            $order = (string)$order->order;
            $this->query->orderBy("$table_name.$column_name", $order);
        }

        return $this;
    }

    /**
     * Lấy dữ liệu cuối
     * 
     * @return array
     */
    public function getData(): array
    {
        Paginator::currentPageResolver(fn () => $this->currentPage);
        $datas = $this->query->paginate($this->perPage);
        if (ApiDebug::check()) {
            $sqlLog[0]['query'] = $this->getSqlString();
            session(['sqlLog' => $sqlLog]);
        }

        return [
            'page'        => $datas->currentPage(),
            'per_page'    => $datas->perPage(),
            'total_count' => $datas->total(),
            'items'       => $this->convertData($datas->items())
        ];
    }

    /**
     * Convert những dữ liệu lấy được từ query (function, alias, ...)
     * 
     * @param array $data Dữ liệu lấy được từ query
     * @return array Dữ liệu sau khi convert
     */
    public function convertData(array $datas): array
    {
        if (sizeof($datas) > 500) return $datas;
        collect($datas)->map(function ($data) {
            foreach ($this->displayColumn as $display_column) {
                if ($display_column['alias_name'] !== '' && $display_column['phpfunction']) {
                    $phpfunction = $display_column['phpfunction'];
                    $alias_name  = $display_column['alias_name'];
                    if (isset($data->$alias_name) && $data->$alias_name !== "") {
                        $data->{$alias_name} = FunctionHelper::{$phpfunction}($data->$alias_name);
                    }
                }
            }
            return $data;
        });

        return $datas;
    }

    /**
     * Lấy t-sql từ query
     * 
     * @return string
     */
    public function getSqlString(): string
    {
        $builder      = $this->query;
        $queryNoValue = $builder->toSql();
        $bindings     = $builder->getBindings();
        $queryNoValue = str_replace('%', '&', $queryNoValue);
        $sql = str_replace('?', "'%s'", $queryNoValue);
        $sql = sprintf($sql, ...$bindings);
        $sql = str_replace('&', '%', $sql);

        return $sql;
    }
}
