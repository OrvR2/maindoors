<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ErrorController;
use App\Http\Requests\Admin\AdminForgotPasswordRequest;
use App\Http\Requests\AdminResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
//    protected $md5Forgot;
//    protected $emailForgot;

//    public function __construct($md5Forgot,$emailForgot)
//    {
//        $this->md5Forgot = $md5Forgot;
//        $this->emailForgot = $emailForgot;
//    }

    public function getForgotPassword(){
        return view('adminlte.pages.forgotpassword');
    }

    public function postForgotPassword(AdminForgotPasswordRequest $request){
        $emailForgot = $request->email;
        $userForgot = User::all()->where('email',$emailForgot)->first();
        if (!empty($userForgot)){
            $md5Forgot = Hash::make($userForgot->fullname . $userForgot->username . $userForgot->confirm_code);
            Mail::to($request->email)->send(new ForgotPassword($md5Forgot,$emailForgot));
            return redirect()->route('admin.forgot.getForgotPassword')->with([
                'msgAlert' => 'Bạn gửi mail thành công , hãy kiểm tra lại hòm mail',
                'lvlAlert' => 'success'
            ]);
        }else{
            return redirect()->route('admin.forgot.getForgotPassword')->with([
                'msgAlert' => 'Email của bạn không tồn tại !',
                'lvlAlert' => 'danger'
            ]);
        }
    }

    public function checkForgot($md5Forgot,$emailForgot){
//        $this->md5Forgot = $md5Forgot;
//        $this->emailForgot = $emailForgot;
        return view('adminlte.pages.resetpassword')->with([
            'md5Forgot' => $md5Forgot,
            'emailForgot' => $emailForgot
        ]);
    }

    public function resetPassword(AdminResetPasswordRequest $request){
        $userForgot = User::all()->where('email',$request->emailForgot)->first();
        if(Hash::check($userForgot->fullname . $userForgot->username . $userForgot->confirm_code, $request->md5Forgot)){
            $userForgot->password = Hash::make($request->password);
            $userForgot->save();
//            dd(Auth::user());
            return redirect()->route('admin.login.getLogin')->with([
                'msgAlert' => 'Bạn đã đổi password thành công . Bạn hãy đăng nhập bằng mật khẩu mới !',
                'lvlAlert' => 'success'
            ]);
        }else{
            $userForgot->save();
            return redirect()->route('error503');
        }
    }
}