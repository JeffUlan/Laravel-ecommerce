<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Channel as Channel;
use Webkul\Tax\Repositories\TaxCategoryRepository as TaxCategory;
use Webkul\Tax\Repositories\TaxRateRepository as TaxRate;
use Webkul\Tax\Repositories\TaxMapRepository as TaxMap;

/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxCategoryController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * TaxCategoryRepository
     *
     * @var mixed
     */
    protected $taxCategory;

    /**
     * TaxRateRepository
     *
     * @var mixed
     */
    protected $taxRate;

    /**
     * TaxMapRepository
     *
     * @var mixed
     */
    protected $taxMap;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Tax\Repositories\TaxCategoryRepository $taxCategory
     * @param  Webkul\Tax\Repositories\TaxRateRepository     $taxRate
     * @param  Webkul\Tax\Repositories\TaxMapRepository      $taxMap
     * @return void
     */
    public function __construct(
        TaxCategory $taxCategory,
        TaxRate $taxRate,
        TaxMap $taxMap
    )
    {
        $this->middleware('admin');

        $this->taxCategory = $taxCategory;

        $this->taxRate = $taxRate;

        $this->taxMap = $taxMap;

        $this->_config = request('_config');
    }

    /**
     * Function to show
     * the tax category form
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view'])->with('taxRates', $this->taxRate->all());
    }

    /**
     * Function to create
     * the tax category.
     *
     * @return view
     */
    public function create()
    {
        $data = request()->input();

        $this->validate(request(), [
            'channel_id' => 'required|numeric',
            'code' => 'required|string|unique:tax_categories,id',
            'name' => 'required|string|unique:tax_categories,name',
            'description' => 'required|string',
            'taxrates' => 'array|required'
        ]);

        if($taxCategory = $this->taxCategory->create(request()->input())) {
            $allTaxCategories = $data['taxrates'];

            //attach the categories in the tax map table
            $this->taxCategory->attachOrDetach($taxCategory, $allTaxCategories);

            session()->flash('success', trans('admin::app.settings.tax-categories.create-success'));

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('admin::app.settings.tax-categories.create-error'));
        }

        return view($this->_config['view']);
    }

    /**
     * To show the edit form form the tax category
     *
     * @return view
     */

    public function edit($id)
    {
        $taxCategory = $this->taxCategory->findOrFail($id);

        return view($this->_config['view'], compact('taxCategory'));
    }

    /**
     * To update the tax category
     *
     * @return view
     */

    public function update($id) {
        $this->validate(request(), [
            'channel_id' => 'required|numeric',
            'code' => 'required|string|unique:tax_categories,code,'.$id,
            'name' => 'required|string|unique:tax_categories,name,'.$id,
            'description' => 'required|string',
            'taxrates' => 'array|required'
        ]);

        $data = request()->input();

        if($taxCategory = $this->taxCategory->update($data, $id)) {
            $taxRates = $data['taxrates'];

            //attach the categories in the tax map table
            $this->taxCategory->attachOrDetach($taxCategory, $taxRates);

            session()->flash('success', trans('admin::app.settings.tax-categories.update-success'));

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('admin::app.settings.tax-categories.update-error'));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->taxCategory->count() == 1) {
            session()->flash('error', trans('admin::app.settings.tax-categories.atleast-one'));
        } else {
            $this->taxCategory->delete($id);

            session()->flash('success', trans('admin::app.settings.tax-categories.delete'));
        }

        return redirect()->back();
    }
}