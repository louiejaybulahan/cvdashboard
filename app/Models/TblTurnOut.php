<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TblTurnOut extends Model
{
    public $search;

    protected $table = 'tbl_turnout_';

    protected $column = ['id', 'region', 'province', 'city', 'brgy','category', 'set', 'setgroup', 'eligibility',  'not_attend_dominant', 
        'attend_dominant', 'attend_del_dominant','outside', 'monitored_dominant','endcoded_approved', 'submitted_deworming','not_encoded_approved', 
        'encoded_under_forcem','non_compliant', 'compliant','remarks_1', 'remarks_2', 'remarks_3', 'remarks_4', 
        'year','period','month','client_status','sex','grade_group','ip','psgc_brgy'];  

    protected $skipColumn = ['id','psgc_brgy','year'];    
    protected $exactQuery = ['id','psgc_brgy'];
    protected $likeQuery = [];

    protected $filters;
    protected $region = [];
    protected $province = [];
    protected $city = [];
    protected $brgy = [];
    protected $category = [];    
    protected $set = [];    
    protected $setgroup = [];    
    protected $eligibility = [];    
    protected $not_attend_dominant = [];    
    protected $attend_dominant = [];    
    protected $attend_del_dominant = [];    
    protected $outside = [];    
    protected $monitored_dominant = [];    
    protected $endcoded_approved = [];    
    protected $submitted_deworming = [];    
    protected $not_encoded_approved = [];    
    protected $encoded_under_forcem = [];    
    protected $non_compliant = [];    
    protected $compliant = [];    
    protected $remarks_1 = [];    
    protected $remarks_2 = [];    
    protected $remarks_3 = [];    
    protected $remarks_4 = [];    
    protected $year = [];    
    protected $period = [];    
    protected $month = [];    
    protected $client_status = [];    
    protected $sex = [];    
    protected $grade_group = [];    
    protected $ip = [];    
    protected $psgc_brgy = [];        
    protected $currentYear;
    protected $sort = 'ASC';
	
    public $query  = '';
    public function getData(){    	    
        return DB::select($this->buildQuery());                
    }
    public function getQuery(){
        return $this->buildQuery();
    }
    protected function buildQuery(){  
        $this->query = '';  
        $this->allowedFilter = array_diff($this->column, $this->skipColumn);
        $tmpFilter = \App\Models\FiltersTurnout::all($this->allowedFilter);
        $this->filters = $tmpFilter->toArray(); // Session::get($this->table);
        $this->currentYear = date('Y'); // stored this in global config
        if(!isset($this->search['year']) OR $this->search['year']=='null'):
            $this->search['year'] = [$this->currentYear];             
        endif;  
        if (!isset($this->search['limit'])){  //($this->search['limit']=='' AND $this->search['limit']==null):           
            $this->search['limit'] = \Config::get('constants.page_limit');
        }
        if (!isset($this->search['page'])){  // ($this->search['page']=='' AND $this->search['page']==null):
            $this->search['page'] = 1;
        }
        if(!isset($this->search['order'])){
            $this->search['order'] = '-';
        }
        if(!isset($this->search['sort'])){
            $this->search['sort'] = $this->sort;
        }
        
        $hasUnion = false;
        foreach($this->search['year'] AS $y){
            if($hasUnion==true) $this->query .= ' UNION ALL ';
            $hasWhere = $this->hasWhere($y);
            $select = '';
            if($this->search['count']==false){
                if(is_array($this->search['select'])){                    
                    $select = implode('\',\'',$this->search['select']);
                }else{ $select = $this->table.$y.'.*'; }
            }else{                 
                if(is_array($this->search['select'])){
                    $select = implode('\',\'',$this->search['select']);
                }else{ $select = 'COUNT(*) AS total'; }
            }
            $this->query .= 'SELECT \''.$y.'\' As y,'.$select.' FROM '.$this->table.$y.(isset($hasWhere)?' WHERE '.$hasWhere:'');
            $hasUnion = true;
        }
        $order = '';
        if($this->search['order'] != '-'){ $order = ' ORDER BY '. $this->search['order'] .' '.(($this->search['sort']!='-')?$this->search['sort']:$this->sort); }
        $limit = '';
        if($this->search['count']==false){
            $limit = ' LIMIT '.$this->search['page'].','.$this->search['limit'];
        }
        $this->query = $this->query.$order.$limit;           
        return $this->query;        
    }
    protected function hasWhere($year){    	
        $where = null;                    
        $skipColumn = array_diff($this->column, $this->skipColumn);
        foreach($this->column AS $toSearch){     
            if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
                if(!in_array($toSearch, $this->skipColumn)){                
                    $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`'.$toSearch.'` REGEXP \''. ((count($this->search[$toSearch])>1)?implode('|',$this->search[$toSearch]):current($this->search[$toSearch])) .'\'';                
                }
                else if(in_array($toSearch,$this->exactQuery)){                                        
                    $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`'.$toSearch.'` = \''.$this->search[$toSearch].'\'';
                }
                else if(in_array($toSearch,$this->likeQuery)){                                        
                    $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`'.$toSearch.'` LIKE \''.$this->search[$toSearch].'%\'';
                }
            }
        }                              
    	return $where;
    }
  
    public function getPaidUnpaidByPeriod($year){
        return DB::table(''.$this->table.$year)->select(DB::raw('COUNT(*) AS total, SUM(grand_tot) as amount, period_cover, paid'))->groupBy(['period_cover','paid'])->get();
    }   
    public function getPaidUnpaidByBarangay($year){
        return DB::table(''.$this->table.$year)->select(DB::raw('province, city_mun_fields, brgy, paid, COUNT(*) as records, SUM(grand_tot) as amount'))->groupBy(['province','city_mun_fields','brgy','paid'])->get();
    }    
    public function getPaidUnpaid($year){       
        return DB::table(''.$this->table.$year)->select(DB::raw('paid, SUM(grand_tot) as amount'))->groupBy(['paid'])->get();
    }   
    public function getTotalBeneficiaryByProvince($year){
        return DB::table(''.$this->table.$year)->select(DB::raw('province, COUNT(*) as records'))->groupBy(['province'])->get();        
    }  
    public function getTotalRows($year){
        return DB::table(''.$this->table.$year)->count();
    }
}
