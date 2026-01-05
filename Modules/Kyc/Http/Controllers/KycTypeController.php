<?php

namespace Modules\Kyc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Kyc\Entities\KycType;
use Modules\Kyc\Entities\KycInformation;
use Session, Auth, Image, File, Str ,Mail;
use App\Helpers\MailHelper;
use Modules\Kyc\Emails\KycVerifactionEmail;
use App\Models\User;

class KycTypeController extends Controller
{

    public function index(){

        $kycType = KycType::orderBy('id', 'desc')->get();

        return view('kyc::Admin.Type.index',compact('kycType'));
    }

    public function create(){
        return view('kyc::Admin.Type.create');
    }

    public function store(Request $request){

        $kyctype = new KycType();
        $kyctype->name = $request->name;
        $kyctype->status = $request->status;
        $kyctype->save();

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function edit($id){
        $kyc = KycType::find($id);
        return view('kyc::Admin.Type.edit',compact('kyc'));
    }

    public function update(Request $request, $id){

        $kyc = KycType::find($id);
        $kyc->name = $request->name;
        $kyc->status = $request->status;
        $kyc->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function destroy($id){
        $kyc = KycType::find($id);
        $kycInfo = KycInformation::where('kyc_id',$id);
        $kycInfo->delete();
        $kyc->delete();

        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function DestroyKyc($id){
        $kyc = KycInformation::find($id);
        $kyc->delete();

        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function UpdateKycStatus(Request $request, $id){

        $kyc = KycInformation::find($id);
        $kyc->status = $request->status;
        $kyc->save();

        $influencer = User::where('id',$kyc->user_id)->first();

        $notification= trans('translate.Updated Successfully');

        $notification2= trans('translate.Your Account Is Verified By KYC');
        MailHelper::setMailConfig();
        try {
            $subject= trans('translate.KYC Verifaction');
            $message = 'Name: ' . $influencer->name . '<br>' . $notification2;

            Mail::to($influencer->email)->send(new KycVerifactionEmail($message,$subject));
        } catch (\Exception $e) {
            \Log::error('Mail send error: ' . $e->getMessage());
        }
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function kycList(){

       $kycs = KycInformation::orderBy('id', 'desc')->get();

        return view('kyc::Admin.kyc.index',compact('kycs'));
    }
}
