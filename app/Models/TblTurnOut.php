<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TblTurnOut extends Model
{
    public $search;

    protected $table = 'tbl_turnout_';

    protected $column = ['id', 'region', 'province', 'city', 'brgy','category', 'set', 'setgroup', 'eligibility',  'not_attend_dominant', 
        'attend_dominant', 'attend_del_dominant','outside', 'monitored_dominant','encoded_approved', 'submitted_deworming','not_encoded_approved', 
        'encoded_under_forcem','non_compliant', 'compliant','remarks_1', 'remarks_2', 'remarks_3', 'remarks_4', 
        'year','period','month','client_status','sex','grade_group','ip','psgc_brgy','REGION_ID','PROVINCE_ID','CITY_ID','BRGY_ID'];  

    protected $skipColumn = ['id','period','year','region','province','city','brgy','psgc_brgy','REGION_ID','PROVINCE_ID','CITY_ID','BRGY_ID'];    
    protected $exactQuery = [];
    protected $likeQuery = [];
    protected $otherTablColumn = ['REGION_ID' => 'lib_regions.REGION_ID','PROVINCE_ID' => 'lib_provinces.PROVINCE_ID','CITY_ID' => 'lib_cities.CITY_ID','BRGY_ID' => 'lib_brgy.BRGY_ID'];

    protected $filters;    
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
        if(!isset($this->search['period']) OR $this->search['period']=='null'):
            $this->search['period'] = [1];             
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
            foreach($this->search['period'] AS $p){
                if($hasUnion==true) $this->query .= ' UNION ALL ';
                $hasWhere = $this->hasWhere($y.'_'.$p);
                $select = '';            
                if($this->search['count']==false){
                    if(is_array($this->search['select'])){                    
                        $this->search['select'] = array_merge($this->search['select'],['lib_brgy.BRGY_NAME','lib_cities.CITY_NAME','lib_provinces.PROVINCE_NAME','lib_regions.REGION_NAME']);
                        $select = implode('\',\'',$this->search['select']);
                    }else{ $select = $this->table.$y.'_'.$p.'.*, lib_brgy.BRGY_NAME,lib_cities.CITY_NAME,lib_provinces.PROVINCE_NAME,lib_regions.REGION_NAME'; }
                }else{                 
                    if(is_array($this->search['select'])){
                        $select = implode('\',\'',$this->search['select']);
                    }else{  $select = 'COUNT(*) AS total';  }
                }
                $leftJoin = ' LEFT JOIN lib_brgy ON lib_brgy.BRGY_ID='.$this->table.$y.'_'.$p.'.brgy_id
                            LEFT JOIN lib_cities ON lib_cities.CITY_ID=lib_brgy.CITY_ID 
                            LEFT JOIN lib_provinces ON lib_provinces.PROVINCE_ID=lib_cities.PROVINCE_ID
                            LEFT JOIN lib_regions ON lib_regions.REGION_ID=lib_provinces.REGION_ID ';
                $this->query .= 'SELECT \''.$this->table.$y.'_'.$p.'\' As y,'.$select.' FROM '.$this->table.$y.'_'.$p.$leftJoin.(isset($hasWhere)?' WHERE '.$hasWhere:'');
                $hasUnion = true;
            }
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
                else if(isset($this->otherTablColumn[$toSearch])){
                    $where .= (($where!=null)?' AND ':'') . ' '.$this->otherTablColumn[$toSearch].' IN (\''. ((count($this->search[$toSearch])>1)?implode('|',$this->search[$toSearch]):current($this->search[$toSearch])) .'\')';
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
