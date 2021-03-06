<?php
namespace tuanlq11\cms\skeleton\module\base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use URL, Route, Response, Session, Request;

/**
 * User: Fallen
 *
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
     * @return Response
     */
    public function index()
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
     *
     * @return Response
     */
    public function show(Request $request, $obj)
    {
        if (!$obj = $this->loadBindingModel($obj)) {
            abort(404, trans('cms.not_found_object'));
        }

        $form = $this->buildForm('show', 'show', $obj);

        return $this->renderView('show', get_defined_vars(), $obj);
    }

    /**
     * Create new object
     *
     * @return  Response
     */
    public function create()
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
     * @return  Response
     */
    public function store()
    {
        /** Get Config from create action */
        $this->action = 'create';

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
                ->withSuccess(trans('message.create_successfully'));
        }

        if (isset($_REQUEST["_saveAndRedirect"])) {
            return redirect()
                ->to($this->getGeneratedUrl('index'))
                ->withSuccess(trans('message.create_successfully'));
        }

        return redirect()
            ->to($this->getGeneratedUrl('edit', [$model->id]))
            ->withSuccess(trans('message.create_successfully'));
    }

    /**
     * Edit page for edit item
     *
     * @param $obj    mixed
     *
     * @return Response
     */
    public function edit($obj)
    {
        if (!$obj = $this->loadBindingModel($obj)) {
            abort(404, trans('cms.not_found_object'));
        }

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
     *
     * @return Response
     */
    public function update($obj)
    {
        /** Get Config from edit action */
        $this->action = 'edit';

        if (!$obj = $this->loadBindingModel($obj)) {
            abort(404, trans('cms.not_found_object'));
        }

        if (isset($_REQUEST["_delete"])) {
            $obj->delete();

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@index', $this->getModuleName()))
                ->withSuccess(trans('message.delete_successfully'));
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
            ->withSuccess(trans('message.update_successfully'));
    }

    /**
     * Delete item
     *
     * @param $obj    Model
     *
     * @return Response
     */
    public function destroy($obj)
    {
        if (!$obj = $this->loadBindingModel($obj)) {
            abort(404, trans('cms.not_found_object'));
        }

        if (empty($obj)) return redirect($this->getGeneratedUrl('index'))->with('Message', trans('delete.notexists'));

        $obj->delete();

        return redirect($this->getGeneratedUrl('index'))->with('Message', trans('delete.success'));
    }

    /**
     * Filter item in list
     *
     * @return Response
     */
    public function filter()
    {
        Route::dispatchToRoute($request = Request::create(URL::previous()));
        $previousAction = explode("@", Route::currentRouteAction())[1];
        $queryParams    = $request->query->all();

        if (isset($queryParams["page"])) unset($queryParams["page"]);

        if (isset($_REQUEST['_btnReset'])) {
            $this->clearFilter();

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@' . $previousAction, $this->getModuleName()), $queryParams);
        }

        if (!$this->validateFilter()) {
            $this->setFilterData($this->getFilter());

            return redirect()
                ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@' . $previousAction, $this->getModuleName()), $queryParams)
                ->withErrors($this->form_filter->getErrors())
                ->with('filter', $this->getFilter());
        }

        $this->saveFilter();

        return redirect()
            ->action(sprintf('\App\Http\Modules\%1$s\%1$sActions@' . $previousAction, $this->getModuleName()), $queryParams);
    }

}