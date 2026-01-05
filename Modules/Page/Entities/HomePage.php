<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected static function newFactory()
    {
        return \Modules\Section\Database\factories\HomePageFactory::new();
    }


    protected $appends = ['home1_intro_short_title', 'home1_intro_title', 'video_short_title', 'video_title', 'counter_title1', 'counter_title2', 'counter_title3', 'dealer_short_title', 'dealer_title','mobile_short_title', 'mobile_title', 'mobile_description', 'callus_title', 'callus_header1', 'callus_header2', 'home2_intro_short_title', 'home2_intro_title', 'home3_intro_short_title', 'home3_intro_title', 'working_step_title1', 'working_step_title2', 'working_step_title3', 'working_step_title4', 'working_step_des1', 'working_step_des2', 'working_step_des3', 'working_step_des4', 'home3_intro_des'];

    public function translate(){
        return $this->belongsTo(HomePageTranslation::class, 'id', 'home_page_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(HomePageTranslation::class, 'id', 'home_page_id')->where('lang_code', front_lang());
    }

    public function getHome1IntroShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home1_intro_short_title;
       }else{
           return $this->translate->home1_intro_short_title;
       }
    }

    public function getHome1IntroTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home1_intro_title;
       }else{
           return $this->translate->home1_intro_title;
       }
    }

    public function getVideoShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->video_short_title;
       }else{
           return $this->translate->video_short_title;
       }
    }

    public function getVideoTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->video_title;
       }else{
           return $this->translate->video_title;
       }
    }

    public function getCounterTitle1Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->counter_title1;
       }else{
           return $this->translate->counter_title1;
       }
    }

    public function getCounterTitle2Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->counter_title2;
       }else{
           return $this->translate->counter_title2;
       }
    }

    public function getCounterTitle3Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->counter_title3;
       }else{
           return $this->translate->counter_title3;
       }
    }

    public function getDealerShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->dealer_short_title;
       }else{
           return $this->translate->dealer_short_title;
       }
    }

    public function getDealerTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->dealer_title;
       }else{
           return $this->translate->dealer_title;
       }
    }

    public function getMobileShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->mobile_short_title;
       }else{
           return $this->translate->mobile_short_title;
       }
    }

    public function getMobileTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->mobile_title;
       }else{
           return $this->translate->mobile_title;
       }
    }

    public function getMobileDescriptionAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->mobile_description;
       }else{
           return $this->translate->mobile_description;
       }
    }

    public function getCallusTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->callus_title;
       }else{
           return $this->translate->callus_title;
       }
    }

    public function getCallusHeader1Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->callus_header1;
       }else{
           return $this->translate->callus_header1;
       }
    }

    public function getCallusHeader2Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->callus_header2;
       }else{
           return $this->translate->callus_header2;
       }
    }

    public function getHome2IntroShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home2_intro_short_title;
       }else{
           return $this->translate->home2_intro_short_title;
       }
    }

    public function getHome2IntroTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home2_intro_title;
       }else{
           return $this->translate->home2_intro_title;
       }
    }

    public function getHome3IntroShortTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home3_intro_short_title;
       }else{
           return $this->translate->home3_intro_short_title;
       }
    }

    public function getHome3IntroTitleAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home3_intro_title;
       }else{
           return $this->translate->home3_intro_title;
       }
    }

    public function getHome3IntroDesAttribute()
    {
        if($this->front_translate){
            return $this->front_translate?->home3_intro_des;
       }else{
           return $this->translate->home3_intro_des;
       }
    }



    public function getWorkingStepTitle1Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_title1;
       }else{
           return $this->translate->working_step_title1;
       }
    }

    public function getWorkingStepTitle2Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_title2;
       }else{
           return $this->translate->working_step_title2;
       }
    }

    public function getWorkingStepTitle3Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_title3;
       }else{
           return $this->translate->working_step_title3;
       }
    }

    public function getWorkingStepTitle4Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_title4;
       }else{
           return $this->translate->working_step_title4;
       }
    }

    public function getWorkingStepDes1Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_des1;
       }else{
           return $this->translate->working_step_des1;
       }
    }

    public function getWorkingStepDes2Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_des2;
       }else{
           return $this->translate->working_step_des2;
       }
    }

    public function getWorkingStepDes3Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_des3;
       }else{
           return $this->translate->working_step_des3;
       }
    }

    public function getWorkingStepDes4Attribute()
    {
        if($this->front_translate){
            return $this->front_translate?->working_step_des4;
       }else{
           return $this->translate->working_step_des4;
       }
    }
































}
