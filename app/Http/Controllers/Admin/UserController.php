<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserAdd(){
        return view('admin.module.user.add');
    }

    public function postUserAdd(UserAddRequest $request){
        $user = new User;
        $user->name = $request->txtUser;
        $user->email = $request->txtEmail;
        $user->password = bcrypt($request->txtPass);
        $user->level = $request->rdoLevel;
        $user->created_at = new DateTime();
        $user->save();
        return redirect()->route('getUserList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Thêm thành viên thành công']);
    }

    public function getUserList(){
        $data = User::select('id', 'name', 'email', 'level')->get()->toArray();
        return view('admin.module.user.list', ['userData'=>$data]);
    }

    public function getUserDel($id){
        $user_current_login = Auth::user()->id;
        $user_delete = User::find($id);
        if(($id == 1) || ($user_current_login != 1 && $user_delete['level'] == 1)){
            return redirect()->route('getUserList')->with(['flash_level'=>'error_msg', 'flash_message'=>'Bạn không được phép xóa người dùng này']);
        }else{
            $user_delete->delete($id);
            return redirect()->route('getUserList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Xóa người dùng thành công']);
        }
    }

    public function getUserEdit($id){
        $data = User::findOrFail($id)->toArray();
        if((Auth::user()->id != 1) && ($id == 1 || $data['level'] == 1 && Auth::user()->id != $id)){
            return redirect()->route('getUserList')->with(['flash_level'=>'error_msg', 'flash_message'=>'Bạn không được phép sửa người dùng này']);
        }
        return view('admin.module.user.edit', ['userData'=>$data]);
    }

    public function postUserEdit(Request $request, $id){
        $user = User::find($id);
        if($request->txtPass){
            $this->validate($request,
                [
                    'txtRepass'=>'same:txtPass'
                ],
                [
                    'txtPass.required'=>'Confirm password không khớp'
                ]
            );
            $user->password = bcrypt($request->txtPass);
        }
        $user->level = $request->rdoLevel;
        $user->updated_at = new DateTime();
        $user->save();
        return redirect()->route('getUserList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Cập nhật thành viên thành công']);
    }
}
