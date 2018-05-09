<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TblNonCompliantHealth extends Model
{
    public $search;
    
    protected $table = 'tbl_noncomp_health_';

    protected $column = ['id', 'region', 'province', 'muni', 'brgy',
        'psgc', 'hh_id', 'entry_id', 'hh_status',  'lastname',
        'firstname','middlename','ext','sex','birthday','ip',
        'pregnant','child','hc_id','hc_name','hc_region','hc_province',
        'hc_muni','hc_brgy','dom_hc_id','dom_hc_name','dom_hc_region',
        'dom_hc_province','dom_hc_muni','dom_hc_brgy','remarks','month',
        'year','period','brgy_id'];  

    protected $skipColumn = ['id','hh_id','entry_id','lastname','firstname','middlename','ext','birthday','brgy_id','hc_id','dom_hc_id','year'];    
    protected $exactQuery = ['id','hh_id','entry_id','ext','birthday','brgy_id','hc_id','dom_hc_id','ext'];
    protected $likeQuery = ['lastname','firstname','middlename','birthday'];
    
    protected $allowedFilter = [];
    protected $filters;
    
    protected $region = [];
    protected $province = [];
    protected $muni = [];
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
    protected $pregnant = [];    
    protected $child = [];    
    protected $hc_id = [];    
    protected $hc_name = [];    
    protected $hc_region = [];    
    protected $hc_province = [];    
    protected $hc_muni = [];    
    protected $hc_brgy = [];    
    protected $dom_hc_id = [];    
    protected $dom_hc_name = [];    
    protected $dom_hc_region = [];    
    protected $dom_hc_province = [];    
    protected $dom_hc_muni = [];
    protected $dom_hc_brgy = [];
    protected $remarks = [];
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
        $merge = array_merge($this->skipColumn,['brgy','psgc','hc_name','hc_brgy','dom_hc_name','dom_hc_brgy']);
        $this->allowedFilter = array_diff($this->column, $merge);
        $tmpFilter = \App\Models\FiltersHealth::all($this->allowedFilter);
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
        /*
        $toSearch = 'household';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
          $where .= ' `'.$this->table.$year.'`.`hh_id` = \''.$this->search[$toSearch].'\'';
        }
        $toSearch = 'lastname';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            $where .= (($where!=null)?' AND ':''). ' `'.$this->table.$year.'`.`lname` LIKE \''.$this->search[$toSearch].'%\'';
        }
        $toSearch = 'firstname';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`fname` LIKE \''.$this->search[$toSearch].'%\'';
        }
        $toSearch = 'middlename';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`mname` LIKE \''.$this->search[$toSearch].'%\'';
        }
        $toSearch = 'period_cover';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`period_cover` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`period_cover` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'modepayment';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`mop` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`mop` = \''.current($this->search[$toSearch]).'\'';
        }        
        $toSearch = 'regspec';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`regular_special_etc` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`regular_special_etc` = \''.current($this->search[$toSearch]).'\'';
        }        
        $toSearch = 'bank';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`lbp_branch` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`lbp_branch` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'set';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`set_no` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`set_no` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'program';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_of_program` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_of_program` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'payroll';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_payrol` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_payrol` = \''.current($this->search[$toSearch]).'\'';
        }

        $toSearch = 'remarks';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`remarks` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`remarks` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'standard';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`standard_remarks` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`standard_remarks` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'registration';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_registration` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`type_registration` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'paid';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1) $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`paid` IN (\''.implode('\',\'',$this->search[$toSearch]).'\')';
            else $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`paid` = \''.current($this->search[$toSearch]).'\'';
        }
        $toSearch = 'province';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){            
            if(count($this->search[$toSearch])>1){ 
                $tmp = null;
                $arr = [];
                foreach($this->search[$toSearch] AS $r){
                    $tmp = explode('|',$r);
                    $arr[] = end($tmp);
                }
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`province` IN (\''.implode('\',\'',$arr).'\')';
            }
            else{                 
                $tmp = explode('|',current($this->search[$toSearch]));
                $item = end($tmp);                
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`province` = \''.$item.'\'';
            }
        }
        $toSearch = 'municipal';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1){
                $tmp = null;
                $arr = [];
                foreach($this->search[$toSearch] AS $r){
                    $tmp = explode('|',$r);
                    $arr[] = end($tmp);
                }
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`city_mun_fields` IN (\''.implode('\',\'',$arr).'\')';
            } 
            else{
                $tmp = explode('|',current($this->search[$toSearch]));
                $item = end($tmp);                
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`city_mun_fields` = \''.$item.'\'';
            }    
        }        
        $toSearch = 'brgy';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            if(count($this->search[$toSearch])>1){
                $tmp = null;
                $arr = [];
                foreach($this->search[$toSearch] AS $r){
                    $tmp = explode('|',$r);
                    $arr[] = end($tmp);
                }
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`brgy` IN (\''.implode('\',\'',$arr).'\')';
            }    
            else{
                $tmp = explode('|',current($this->search[$toSearch]));
                $item = end($tmp);  
                $where .= (($where!=null)?' AND ':'') . ' `'.$this->table.$year.'`.`brgy` = \''.$item.'\'';
            }    
        }         
        */
        /*
        $toSearch = 'datefrom';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            $where .= ' `'.$this->table.$year.'`.`mname` LIKE \''.$this->search[$toSearch].'%\'';
        }
        $toSearch = 'dateto';
        if(isset($this->search[$toSearch]) AND $this->search[$toSearch]!='null'){
            $where .= ' `'.$this->table.$year.'`.`mname` LIKE \''.$this->search[$toSearch].'%\'';
        }        
        */                
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
