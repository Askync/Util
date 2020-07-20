<?php

namespace Askync\Utils\Traits;


/**
 * GFilter
 */

trait AskyncModelFilter
{
    public $allowedFilters = ['status'];
    public $sortableFields = ['id', 'name', 'status', 'created_at', 'updated_at'];
    public $searchQuery = '';
    public $searchInTable = [];
    public $minimalSearchChar = 3;
    public $createdAtField = 'created_at';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->searchInTable = $this->searchInTableReplacement ?? $this->searchInTable;
        $this->sortableFields = $this->sortableFieldsReplacement ?? $this->sortableFields;
        $this->minimalSearchChar = $this->minimalSearchCharReplacement ?? $this->minimalSearchChar;
        $this->createdAtField = $this->createdAtFieldReplacement ?? $this->createdAtField;
        $this->allowedFilters = $this->allowedFiltersReplacement ?? $this->allowedFilters;
        $this->setPerPage( (($perPage=app()->request->input('per_page'))>1 && $perPage < 81) ? $perPage : 15 );
    }


    static public function AskyncPerPage()
    {
        return (($perPage=app()->request->input('per_page'))>1 && $perPage < 81) ? $perPage : 15;
    }

    public function tdata()
    {
        $this->validateGVariables();
        $data = $this->query();
        if( strlen( app()->request->input('query') ) >= $this->minimalSearchChar ) {
            if( count($this->searchInTable) > 0 ) {
                $data->where(function ($q){
                    $where = 'and';
                    foreach ($this->searchInTable as $skey=>$sField) {
                        foreach ( explode(' ', app()->request->input('query')) as $key=>$qry ) {
                            if($key < 5) {
                                if ($where == 'and') {
                                    $where = 'or';
                                    $q->where(sprintf("%s.%s", $this->getTable(), $sField), 'like', "%" . $qry . "%");
                                } else {
                                    $q->orWhere(sprintf("%s.%s", $this->getTable(), $sField), 'like', "%" . $qry . "%");
                                }
                            }
                        }
                    }
                });
            }
        }
        if( app()->request->has('sort_by') ) {
            if( in_array( app()->request->input('sort_by'), $this->sortableFields) ) {
                $sort = app()->request->input('sort', 'desc') == 'desc' ? 'DESC' : 'ASC';
                $data->orderBy( sprintf('%s.%s', $this->table, app()->request->input('sort_by')), $sort);
            }
        } else {
            $data->latest(sprintf("%s.%s",$this->table, $this->createdAtField));
        }
        $perPage = (($perPage=app()->request->input('per_page'))>1 && $perPage < 81) ? $perPage : $this->perPage;

        if(app()->request->has('from') && app()->request->has('to')) {
            $data->whereBetween($this->createdAtField,[
                \Carbon\Carbon::parse(app()->request->input('from') /1000)->subDay(),
                \Carbon\Carbon::parse(app()->request->input('to') /1000)->addDay(),
            ]);
            if( app()->request->input('per_page') == -1 ) {
                $this->perPage = 9999999;
            }
        }

        if( app()->request->has('filter') ) {
            $filters = explode(',', app()->request->input('filter'));
            foreach ($filters as $f) {
                $tf = explode(':', $f);
                if( in_array($tf[0], $this->allowedFilters) ) {
                    $data->whereIn( filter_var($tf[0], FILTER_SANITIZE_STRING ), explode(';', $tf[1]) );
                }
            }
        }
        return $data;
    }
    function validateGVariables()
    {
        if(!isset($this->allowedFilters)) {
            throw new \Exception( 'allowedFilters not found on ' . self::class );
        }
        if(!isset($this->sortableFields)) {
            throw new \Exception( 'sortableFields not found on ' . self::class );
        }
        if(!isset($this->searchQuery)) {
            throw new \Exception( 'searchQuery not found on ' . self::class );
        }
        if(!isset($this->minimalSearchChar)) {
            throw new \Exception( 'minimalSearchChar not found on ' . self::class );
        }
    }
}
