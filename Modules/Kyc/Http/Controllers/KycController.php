<?php

namespace Modules\Kyc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Kyc\Entities\KycInformation;
use Modules\Kyc\Entities\KycType;
use Session, Auth, Image, File, Str ,Mail;
use App\Helpers\MailHelper;
use Modules\Kyc\Emails\KycVerifactionEmail;
use App\Models\Homepage;
use App\Models\Setting;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function kyc(){

        $influencer = Auth::guard('web')->user();

        $kyc = KycInformation::where(['user_id' => $influencer->id])->first();
        $kycType = KycType::orderBy('id', 'desc')->get();

        return view('kyc::User.kyc.index',compact('kyc','kycType'));
    }

    public function kycSubmit(Request $request){
        $influencer = Auth::guard('web')->user();
        $rules = [
            'kyc_id'=>'required',
            'file'=>'required',
        ];
        $customMessages = [
            'kyc_id.required' => trans('translate.Type of is required'),
            'file' => trans('translate.File is required'),
        ];

        $request->validate($rules,$customMessages);

        $kyc = new KycInformation();

        if($request->file) {
            $image_path = uploadFile($request->file, 'uploads/custom-images');
            $kyc->file = $image_path;
        }

        $kyc->kyc_id = $request->kyc_id;
        $kyc->user_id = $influencer->id;
        $kyc->message = $request->message;
        $kyc->status = 0;
        $kyc->save();

        $notification= trans('translate.Information Submited Successfully. Pls Wait for Conformation');
        MailHelper::setMailConfig();

        try {
            $subject= trans('translate.KYC Verifaction');
            $message = 'Name: ' . $influencer->name . '<br>' . $notification;

            Mail::to($influencer->email)->send(new KycVerifactionEmail($message,$subject));
        } catch (\Exception $e) {
            \Log::error('Mail send error: ' . $e->getMessage());
        }
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}

