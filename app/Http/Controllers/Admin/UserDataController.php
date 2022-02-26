<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserDataRepository;
use App\Repositories\Admin\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserDataController extends Controller
{
    public function __construct(
        protected UserDataRepository $userDataRepository,
        protected UserRepository $userRepository
    )
    {}

    public function index()
    {
        $users  = $this->userRepository->getList();

        return view('admin.user.index',compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $this->userRepository->create($request->all());

        return redirect()->route('admin.user.index')->with(['success'=>'New user created']);
    }

    public function banned($id)
    {
        $this->userRepository->hide($id);

        return redirect()->back();
    }

    public function approved($id)
    {
        $this->userRepository->restore($id);

        return redirect()->back();
    }

    public function edit($id)
    {
        $roles =  Role::all();
        $user = $this->userRepository->show($id);

        return view('admin.user.edit',compact(
            'roles',
            'user'
        ));
    }

    public function update(Request $request, $id)
    {
        if ($request->input('approved')) {
            $this->approved($id);
        }else {
            $this->banned($id);
        }

        $this->userRepository->removeAllRoles($id);

        foreach ($request->input('roles') as $role){
            $this->userRepository->setRole($id,$role);
        }

        $this->userDataRepository->update(
            array_merge($request->all(),['user_id'=>$id])
        );

        return redirect()->route('admin.user.index')
            ->with(['success'=>'Successfully saved user data']);
    }

    public function destroy($id)
    {
        $this->userRepository->destroy($id);

        return redirect()->back();
    }
}
