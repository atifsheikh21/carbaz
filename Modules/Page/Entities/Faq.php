<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected $appends = ['question', 'answer'];

    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\FaqFactory::new();
    }

    public function translate(){
        return $this->belongsTo(FaqTranslate::class, 'id', 'faq_id')->where('lang_code' , admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(FaqTranslate::class, 'id', 'faq_id')->where('lang_code' , front_lang());
    }

    public function getQuestionAttribute()
    {
        return $this->front_translate->question;
    }

    public function getAnswerAttribute()
    {
        return $this->front_translate->answer;
    }


}
