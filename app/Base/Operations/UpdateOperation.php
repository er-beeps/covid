<?php

namespace App\Base\Operations;

use Illuminate\Support\Facades\Route;

trait UpdateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $name       Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupUpdateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/edit', [
            'as'        => $routeName.'.edit',
            'uses'      => $controller.'@edit',
            'operation' => 'update',
        ]);
        Route::get($segment.'/{id}/edit_ajax', [
            'as'        => $routeName.'.edit_ajax',
            'uses'      => $controller.'@edit_ajax',
            'operation' => 'update',
        ]);
        Route::put($segment.'/{id}/update_ajax', [
            'as'        => $routeName.'.update_ajax',
            'uses'      => $controller.'@update_ajax',
            'operation' => 'update_ajax',
        ]);
        Route::put($segment.'/{id}', [
            'as'        => $routeName.'.update',
            'uses'      => $controller.'@update',
            'operation' => 'update',
        ]);

        Route::get($segment.'/{id}/translate/{lang}', [
            'as'        => $routeName.'.translateItem',
            'uses'      => $controller.'@translateItem',
            'operation' => 'update',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupUpdateDefaults()
    {
        $this->crud->allowAccess('update');

        $this->crud->operation('update', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();

            if ($this->crud->getModel()->translationEnabled()) {
                $this->crud->addField([
                    'name' => 'locale',
                    'type' => 'hidden',
                    'value' => $this->request->input('locale') ?? app()->getLocale(),
                ]);
            }
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButton('line', 'update', 'view', 'crud::buttons.update', 'end');
        });
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit_ajax($id)
    {
        $this->enableDialog(true);
        $this->crud->removeAllFields();
        $this->setupUpdateOperation();
        // $this->crud->setEditView('admin.scheme.edit_ajax');
        return $this->edit($id);
    }

    public function update_ajax(){
        $this->enableDialog(true);
        // $this->crud->model->setGuarded(['id','code']);
        // $this->crud->entity->setGuarded(['id','code']);
        $this->crud->removeAllFields();
        $this->setupUpdateOperation();
        // $this->crud->setEditView('admin.scheme.edit_ajax');
        return $this->update();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;
        // if(null !== $this->request->get("async")){
        //     $this->enableDialog = true;
        // }
        $enableDialog =  property_exists($this, 'enableDialog') ? $this->enableDialog : false;
        if ($enableDialog) {
            $view = view($this->crud->getEditView(), $this->data)->renderSections()['content'];
        } else {
            $view = view($this->crud->getEditView(), $this->data);
        }
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return $view;
    }

    /**
     * Update the specified resource in the database.
     *
     * @return Response
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // update the row in the db
        // $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
        //                     $this->crud->getStrippedSaveRequest());
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
        $request->except(['save_action', 'http_referrer', '_token']));
        $this->data['entry'] = $this->crud->entry = $item;
        // if($this->enableDialog)
        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
