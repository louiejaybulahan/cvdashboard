<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TblNonCompliantFds extends Model
{
    public $search;
    
    protected $table = 'tbl_noncomp_fds_';

    protected $column = ['id', 'region', 'province', 'municipality', 'brgy','psgc', 'hh_id', 'entry_id', 'hh_status',  'lastname',
        'firstname','middlename','ext','sex','birthday','ip','month','year','period','brgy_id','REGION_ID','PROVINCE_ID','CITY_ID','BRGY_ID'];  

    protected $skipColumn = ['id','hh_id','entry_id','lastname','firstname','middlename','ext','birthday','brgy_id','year','brgy','psgc','REGION_ID','PROVINCE_ID','CITY_ID','BRGY_ID'];    
    protected $exactQuery = ['id','hh_id','entry_id','ext','birthday','brgy_id','ext'];
    protected $likeQuery = ['lastname','firstname','middlename','birthday'];
    protected $otherTablColumn = ['REGION_ID' => 'lib_regions.REGION_ID','PROVINCE_ID' => 'lib_provinces.PROVINCE_ID','CITY_ID' => 'lib_cities.CITY_ID','BRGY_ID' => 'lib_brgy.BRGY_ID'];
    
    protected $allowedFilter = [];
    protected $filters;
    
    protected $region = [];
    protected $province = [];
    protected $municipality = [];
    protected $brgy = [];    
    protected $psgc = [];        
    protected $hh_id = [];    
    protected $entry_id = [];
    protected $hh_status = [];        
    protected $lastname = [];    
    protected $firstname = [];    
    protected $middlename = [];    
    protected $ext = [];    
    protected $sex = [];    
    protected $birthday = [];    
    protected $ip = [];        
    protected $month = [];    
    protected $year = [];    
    protected $period = [];    

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
        $merge = $this->skipColumn; // array_merge($this->skipColumn,['psgc']);
        $this->allowedFilter = array_diff($this->column, $merge);
        $tmpFilter = \App\Models\FiltersFds::all($this->allowedFilter);
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
                    $this->search['select'] = array_merge($this->search['select'],['lib_brgy.BRGY_NAME','lib_cities.CITY_NAME','lib_provinces.PROVINCE_NAME','lib_regions.REGION_NAME']);
                    $select = implode('\',\'',$this->search['select']);
                }else{ $select = $this->table.$y.'.*, lib_brgy.BRGY_NAME,lib_cities.CITY_NAME,lib_provinces.PROVINCE_NAME,lib_regions.REGION_NAME'; }
            }else{                 
                if(is_array($this->search['select'])){
                    $select = implode('\',\'',$this->search['select']);
                }else{ $select = 'COUNT(*) AS total'; }
            }
            $leftJoin = ' LEFT JOIN lib_brgy ON lib_brgy.BRGY_ID='.$this->table.$y.'.brgy_id
                          LEFT JOIN lib_cities ON lib_cities.CITY_ID=lib_brgy.CITY_ID 
                          LEFT JOIN lib_provinces ON lib_provinces.PROVINCE_ID=lib_cities.PROVINCE_ID
                          LEFT JOIN lib_regions ON lib_regions.REGION_ID=lib_provinces.REGION_ID ';
    		$this->query .= 'SELECT \''.$y.'\' As y,'.$select.' FROM '.$this->table.$y.$leftJoin.(isset($hasWhere)?' WHERE '.$hasWhere:'');
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
