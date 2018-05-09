<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    public $timestamps = false;
    public static function getValue($value){
        //select()->where('handler','PERIOD_START')->first()    
        if(!is_array($value)){
            $r = parent::select('value')->where('handler',$value)->first();
            return $r->value;
        }else{
            $data = [];
            $record = parent::whereIn('handler',$value)->get();            
            foreach($record AS $r){
                $data[$r->handler] = $r->value;
            }             
            return $data;
        }        
    }
    public static function setValue($handler,$value){        
        $conf = parent::firstOrNew(['handler' => trim($handler)]);
        //$conf->fill(['handler' => trim($handler),'value' => $value])->save();
        $conf->handler = $handler;
        $conf->value = $value;
        $conf->save();
    }
}
