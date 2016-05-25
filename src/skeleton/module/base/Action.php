<?php

namespace tuanlq11\cms\skeleton\module\base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use URL, Route, Response, View, Redirect, Session, Request;

/**
 * User: Mr.Tuan
 * @method  \Illuminate\Database\Eloquent\Builder buildQuery()
 * @method LengthAwarePaginator pagination(Builder $query, $page = null)
 * @method void clearFilter()
 * @method void setFilterData(array $filterData)
 * @method Model getModel()
 */
trait Action
{

    /**
     * List object items
     *
     * @param $locale string
     *
     * @return Response
     */
    public function index($locale = 'en')
    {
        $items = $this->pagination($this->buildQuery())->setPath($this->getGeneratedUrl('index'));
        $this->generateHTMLObjAction($items);

        $filter = $this->form_filter;
        if (Session::has('filter')) {
            $this->setFilterData(Session::get('filter'));
        }

        return $this->renderView('index', get_defined_vars());
    }

    /**
     * Show detail of item
     *
     * @param $obj     Model
     * @param $request Request
     * @param $locale  string
     *
     * @return Response
     */
    public function show(Request $request, $obj, $locale = 'en')
    {
        $form = $this->buildForm('show', 'show', $obj);

        return $this->renderView('show', get_defined_vars());
    }

    /**
     * Create new object
     *
     * @param $locale string
     *
     * @return  Response
     */
    public function create($locale = 'en')
    {
        $this->buildForm('create', 'store');
        if (Session::has('form')) {
            $this->setFormData(Session::get('form'));
        }
        $form = $this->form;

        return $this->renderView('create', get_defined_vars());
    }

    /**
     * Create new object
     *
     * @param $locale string
     *
     * @return  Response
     */
    public function store($locale = 'en')
    {
        $this->buildForm('create', 'store');
        $data = Input::get('create');
        if (!$this->validateForm(null, 'create')) {
            $this->setFormData($data);

            return redirect()
                ->to($this->getGeneratedUrl('create'))
                ->withErrors($this->form->getErrors())
                ->with('form', $data);
        }

        $modelName = $this->getModelName();
        $model     = new $modelName();
        $model     = $this->applyDataToObject($model, $data);
        $model->save();

        if (isset($_REQUEST["_saveAndCreate"])) {
            return redirect()
                ->to($this->getGeneratedUrl('create'))
                ->withSuccess("Create Success");
        }

        return redirect()
            ->to($this->getGeneratedUrl('edit', [$model->id]))
            ->withSuccess("Create Success");
    }

    /**
     * Edit page for edit item
     *
     * @param $locale string
     * @param $obj    mixed
     *
     * @return Response
     */
    public function edit($obj, $locale = 'en')
    {
        $this->buildForm('edit', 'update', $obj);
        if (Session::has('form')) {
            $this->setFormData(Session::get('form'));
        }
        $form = $this->form;

        return $this->renderView('edit', get_defined_vars(), $obj);
    }

    /**
     * Update exists item
     *
     * @param  $obj    Model
     * @param  $locale string
     *
     * @return Response
     */
    public function update($obj, $locale = 'en')
    {
        if (isset($_REQUEST["_delete"])) {
            $obj->delete();

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@index', $this->getModuleName()))
                ->withSuccess("Delete Success");
        }

        $this->buildForm('edit', 'update', $obj);
        $data = Input::get('edit');
        if (!$this->validateForm()) {
            $this->setFormData($data);

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@edit', $this->getModuleName()), $obj)
                ->withErrors($this->form->getErrors())
                ->with('form', $data);
        }
        $obj = $this->applyDataToObject($obj, $data);
        $obj->save();

        return redirect()
            ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@edit', $this->getModuleName()), $obj)
            ->withSuccess('Update Success');
    }

    /**
     * Delete item
     *
     * @param $obj    Model
     * @param $locale string
     *
     * @return Response
     */
    public function destroy($obj, $locale = 'en')
    {
        if (empty($obj)) return redirect($this->getGeneratedUrl('index'))->with('Message', trans('delete.notexists'));

        $obj->delete();

        return redirect($this->getGeneratedUrl('index'))->with('Message', trans('delete.success'));
    }

    /**
     * Filter item in list
     *
     * @param $locale string
     *
     * @return Response
     */
    public function filter($locale = 'en')
    {
        Route::dispatchToRoute(Request::create(URL::previous()));
        $previousAction = $this->getCurrentAction();
        if (!$previousAction) {
            /** TODO: Throw error */
        }

        if (isset($_REQUEST['_btnReset'])) {
            $this->clearFilter();

            return redirect(URL::previous());
        }

        if (!$this->validateFilter()) {
            $this->setFilterData($this->getFilter());

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@' . $previousAction, $this->getModuleName()))
                ->withErrors($this->form_filter->getErrors())
                ->with('filter', $this->getFilter());
        }

        $this->saveFilter();

        return redirect(URL::previous());
    }

}