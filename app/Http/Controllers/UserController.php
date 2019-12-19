<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Http\Requests\UserStoreRequest;
use Messtechnik\Models\Client;
use Messtechnik\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    /**
     * Para o admin, retorna uma lista com todos os usuarios,
     * para um usuário comum, retorna uma lista dos usuarios da mesma empresa.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $search = $request['search'];

        Auth::user()->hasRole('Admin') ?
            $usuarios = User::where('name','ilike','%'.$search.'%')
                ->with('cliente')
                ->orWhereHas('cliente', function($query) use ($search) {
                    $query->where('razaosocial', 'ilike','%'.$search.'%');
                })
                ->paginate(10) :
            $usuarios = User::with('cliente')
                ->where('cliente_codigo', Auth::user()->empresa_id)
                ->where('name','like','%'.$search.'%')
                ->paginate(10);

        return view('userlist', compact('usuarios'));
    }

    /**
     * Registra um novo usuário
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request) {
        $cliente = Client::where('razaosocial', $request['razaosocial'])->first();

        $user = new User($request->validated());
        $user->password = bcrypt($user->password);
        dd($cliente);
        $user->cliente_codigo = $cliente->codigo;

        $user->assignRole($request['accountRole']);
        $user->save();

        Session::flash('message', 'Usuário cadastrado com sucesso!');
        return redirect()->back();
    }

    /**
     * Mostra a view para registrar novo usuário
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        Auth::user()->hasRole('Admin') ?
            $clientes = Client::all('codigo', 'razaosocial') :
            $clientes = Client::all('codigo', 'razaosocial')
                ->where('codigo', Auth::user()->cliente_codigo);

        return view('auth.register', compact('clientes'));
    }

    /**
     * Deletar usuário
     *
     * @param $user_id
     */
    public function destroy($user_id) {
        User::find($user_id)->delete();
    }

    /**
     * Mostra a view para editar um usuário
     *
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function edit($user_id) {
        $user = User::find($user_id);
        $role = $user->getRoleNames()->first();

        return view('userconfig', compact(['user', 'role']));
    }

    /**
     * Editar os dados cadastrais de um usuário
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($user_id, Request $request) {
        $user = User::find($user_id);

        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->genero = $request->input('genero');
        $user->aniversario = $request->input('aniversario');

        if ($user->hasRole('Admin')) {
            $user->getRoleNames()->first();
        }
        else {
            $role = $request['accountRole'];
            $this->switchRole($user, $role);
        }

        $user->save();
        return view('userconfig', compact(['user', 'role']));
    }

    /**
     * Altera o tipo de conta de um usuário
     *
     * @param $user
     * @param $role
     */
    public function switchRole($user, $role) {
        if($role === 'Master') {
            $user->assignRole('Master');
            $user->removeRole('Basica');
        }
        elseif ($role === 'Basica') {
            $user->assignRole('Basica');
            $user->removeRole('Master');
        }
    }

    public function editUserAvatar(Request $request)
    {

    }

//    public function editUserAvatar($user_id, Request $request) {
//        $user = User::find($user_id);
//        $role = $user->getRoleNames()->first();
//
//        if($request->hasFile('avatar')) {
//            if ($request->file('avatar')->isValid()) {
//                $user
//                    ->addMedia($request->file('avatar'))
//                    ->toMediaCollection('profile');
//            }
//        }
//
//        return view('userconfig', compact(['user', 'role']));
//    }

    public function showEditPassword() {
        return view('userpassword');
    }

    public function editPassword(Request $request) {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($validatedData['new-password']);
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");
    }

}