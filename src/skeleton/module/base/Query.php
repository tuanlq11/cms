<?php

namespace tuanlq11\cms\skeleton\module\base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Input, Schema;

/**
 * Class Query
 * @method array getConfig(string $key = '')
 * @method void applyFilter(Builder &$query)
 * @method string getModuleName()
 * @method string getModelName()
 * @property string action
 * @package Core\Bases\Module\Base
 */
trait Query
{
    /** @var  Model */
    protected $model;

    /** @var  Builder */
    protected $query;

    /**
     * Get Model object
     * @return Model
     */
    protected function getModel()
    {
        if (!class_exists($this->getModelName())) {
            return false;
        }

        if (is_null($this->model)) {
            $modelName   = $this->getModelName();
            $this->model = new $modelName;
        }

        return $this->model;
    }

    /**
     * Build query for index
     *
     * @param $fieldsConfig array
     *
     * @return Builder
     */
    protected function buildQuery($fieldsConfig = null)
    {
        $action       = $this->action;
        $fieldsConfig = $fieldsConfig ? $fieldsConfig : $this->getListFieldsConfig();
        $fieldsKey    = array_keys($fieldsConfig);
        $order_by     = $this->getConfig("{$action}.order_by");

        $model_class = $this->getModelName();
        if (!$model_class) {
            $message = env('APP_DEBUG') ? "Not found model provider for module!" : "";
            abort(404, $message);
        }

        $is_i18n = method_exists($model_class, 'saveI18N');
        /** @var Builder $query */
        $query     = $is_i18n ? $model_class::I18N($this->getCurrentLocale()) : $model_class::query();
        $tableName = (new $model_class())->getTable();
        // $primary   = (array)(new $modelName)->getKeyName();

        $availableField = Schema::getColumnListing($tableName);
        $fieldsKey      = array_filter($fieldsKey, function ($val) use ($availableField) {
            return in_array($val, $availableField);
        });

        if (!empty($fieldsKey) && is_array($fieldsKey) && isset($fieldsKey[0]) && $fieldsKey[0] != '*') {
//            $finalFieldList = array_unique(array_merge($primary, $fieldsKey));
//            $query->select($finalFieldList);
            /** TODO: Current select all */
        }

        $this->applyFilter($query);

        foreach ($order_by as $sortData) {
            $splitData = explode(':', $sortData);
            $col       = $splitData[0];
            if (!in_array($col, $availableField)) continue;
            $method = isset($splitData[1]) ? $splitData[1] : 'asc';

            $query->orderBy($col, $method);
        }


        return $query;
    }

    /**
     * Pagination for query
     *
     * @param $query
     *
     * @return LengthAwarePaginator
     */
    protected function pagination(Builder $query, $page = null)
    {
        $action = $this->action;
        $page   = is_null($page) ? Input::get('page') : $page;

        $max_per_page = $this->getConfig($action)['max_per_page'];
        return $query->paginate($max_per_page, ['*'], 'page', $page);
    }

    /**
     * Create new object.
     * @var $obj Model
     * @var $data array
     *
     * @return Model
     */
    protected function applyDataToObject($obj, $data)
    {
        $defaultFunc = "applyData";

        foreach ($data as $key => $value) {
            $applyFunc = "apply{$key}Data";
            call_user_func_array([$this, method_exists($this, $applyFunc) ? $applyFunc : $defaultFunc], [&$obj, $key, $value]);
        }

        return $obj;
    }

    /**
     * Apply data to object
     * @var $obj Model
     * @var $key string
     * @var $value string
     *
     * @return mixed
     */
    protected function applyData(&$obj, $key, $value)
    {
        $obj->$key = $value;
    }
}