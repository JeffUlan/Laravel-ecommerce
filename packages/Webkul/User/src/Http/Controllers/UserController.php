<?php

namespace Webkul\User\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\User\Repositories\AdminRepository;
use Webkul\User\Repositories\RoleRepository;
use Webkul\User\Http\Requests\UserForm;
use Hash;

/**
 * Admin user controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UserController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AdminRepository object
     *
     * @var Object
     */
    protected $adminRepository;

    /**
     * RoleRepository object
     *
     * @var Object
     */
    protected $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\User\Repositories\AdminRepository $adminRepository
     * @param  \Webkul\User\Repositories\RoleRepository $roleRepository
     * @return void
     */
    public function __construct(
        AdminRepository $adminRepository,
        RoleRepository $roleRepository
    )
    {
        $this->adminRepository = $adminRepository;

        $this->roleRepository = $roleRepository;

        $this->_config = request('_config');

        $this->middleware('guest', ['except' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View 
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View 
     */
    public function create()
    {
        $roles = $this->roleRepository->all();

        return view($this->_config['view'], compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\User\Http\Requests\UserForm  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserForm $request)
    {
        $data = $request->all();

        if (isset($data['password']) && $data['password'])
            $data['password'] = bcrypt($data['password']);

        Event::fire('user.admin.create.before');

        $admin = $this->adminRepository->create($data);

        Event::fire('user.admin.create.after', $admin);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'User']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id
     * @return \Illuminate\View\View 
     */
    public function edit($id)
    {
        $user = $this->adminRepository->findOrFail($id);

        $roles = $this->roleRepository->all();

        return view($this->_config['view'], compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\User\Http\Requests\UserForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserForm $request, $id)
    {
        $data = $request->all();

        if (! $data['password'])
            unset($data['password']);
        else
            $data['password'] = bcrypt($data['password']);

        if (isset($data['status'])) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        Event::fire('user.admin.update.before', $id);

        $admin = $this->adminRepository->update($data, $id);

        Event::fire('user.admin.update.after', $admin);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'User']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View 
     */
    public function destroy($id)
    {
        $user = $this->adminRepository->findOrFail($id);

        if ($this->adminRepository->count() == 1) {
            session()->flash('error', trans('admin::app.response.last-delete-error', ['name' => 'Admin']));
        } else {
            Event::fire('user.admin.delete.before', $id);

            if (auth()->guard('admin')->user()->id == $id) {
                return view('admin::customers.confirm-password');
            }

            try {
                $this->adminRepository->delete($id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Admin']));

                Event::fire('user.admin.delete.after', $id);

                return response()->json(['message' => true], 200);
            } catch (Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Admin']));
            }
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * destroy current after confirming
     *
     * @return mixed
     */
    public function destroySelf()
    {
        $password = request()->input('password');

        if (Hash::check($password, auth()->guard('admin')->user()->password)) {
            if ($this->adminRepository->count() == 1) {
                session()->flash('error', trans('admin::app.users.users.delete-last'));
            } else {
                $id = auth()->guard('admin')->user()->id;

                Event::fire('user.admin.delete.before', $id);

                $this->adminRepository->delete($id);

                Event::fire('user.admin.delete.after', $id);

                session()->flash('success', trans('admin::app.users.users.delete-success'));

                return redirect()->route('admin.session.create');
            }
        } else {
            session()->flash('warning', trans('admin::app.users.users.incorrect-password'));

            return redirect()->route($this->_config['redirect']);
        }
    }
}
