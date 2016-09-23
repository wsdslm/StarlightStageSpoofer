<?php
namespace App\Repositories;

use Schema;
use App\GameUser;

class Repository {
    private $className;
    private $model;
    private $columns;
    private $with;
    private $limit;
    private $orderBy;

    public function __construct($className) {
        $this->className = $className;
        $this->model = new $className;
        $this->columns = Schema::getColumnListing($this->getTable());
    }

    public function get($arg=null, $opt=null) {
        return $this->query($arg, $opt)->get();
    }

    public function first($arg=null, $opt=null) {
        return $this->query($arg, $opt)->first();
    }

    public function delete($arg=null, $opt=null) {
        return $this->query($arg, $opt)->delete();
    }

    public function query($arg=null, $opt=null) {
        $query = $this->model->newQuery();

        if (is_callable($arg)) {
            $arg($query);
        } else if (is_array($arg)) {
            foreach ($arg as $key => $val) {
                $query->where($key, $val);
            }
        } else if (isset($opt)) {
            $query->where($arg, $opt);
        } else if (isset($arg)) {
            $query->where($this->getKeyName(), $arg);
        }

        if (isset($this->limit)) $query->take($this->limit);
        if (isset($this->with)) $query->with($this->with);
        if (isset($this->orderBy)) {
            $column = $this->orderBy['column'];
            $order = $this->orderBy['order'];
            $query->orderBy($column, $order);
        }

        return $query;
    }

    public function insertOrUpdate($queryArray, $data, $extraData=[]) {
        $model = $this->query($queryArray)->firstOrNew($queryArray);
        $model = $this->applyAttributes($model, array_merge($data, $extraData));
        $model->save();
        return $model;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy($column, $order='asc') {
        $this->orderBy = [
            "column" => $column,
            "order" => $order
        ];
        return $this;
    }

    public function with($with) {
        if (is_null($this->with)) $this->with = [];
        if (!is_array($with)) $with = [ $with ];
        $this->with += $with;
        return $this;
    }

    protected function applyAttributes($model, $data) {
        foreach ($data as $key => $val) {
            if (in_array($key, $this->getColumns())) {
                $model->$key = $val;
            }
        }
        return $model;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getModel() {
        return $this->model;
    }

    public function getTable() {
        return $this->model->getTable();
    }

    public function getKeyName() {
        return $this->model->getKeyName();
    }

    public function getColumns() {
        return $this->columns;
    }
}
