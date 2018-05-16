@php
use Illuminate\Support\Facades\Session; 
@endphp
<html>
    <head>
        <title>Uploading Non Complaint Data</title>
        <style type="text/css">
            body{ font-family: monospace,tahoma; font-size: 11px; }
        </style>
        <script type="text/javascript">
            //var speed = 9999999;
            var speed = 9999;
            var timer = null;
            var currentpos = 0, alt = 1, curpos1 = 1, curpos2 = -1;
            function initialize() {
                startit();
            }
            function scrollwindow() {
                if (document.all && !document.getElementById) temp = document.body.scrollTop;
                else temp = window.pageYOffset;
                if (alt == 0) alt = 2;
                else alt = 1;
                if (alt == 0) curpos1 = temp;
                else curpos2 = temp;
                if (curpos1 != curpos2) {
                    if (document.all) currentpos = document.body.scrollTop + speed;
                    else currentpos = window.pageYOffset + speed;
                    window.scroll(0, currentpos);
                }
                else {
                    currentpos = 0;
                    window.scroll(0, currentpos);
                }
            }
            function startit() { timer = setInterval("scrollwindow()", 100); }
            function stopInterval() { clearInterval(timer); }
            window.onload = initialize();
        </script>
    </head>
<body>
    @if($sesdata['position']<$sesdata['numberOfFiles'])
    <div style="position: fixed;  background: yellowgreen; top: 45%; left: 35%; z-index: 100;text-align:center; padding: 10px;">
        <strong id="divPercentage" style="font-size: 35px;"></strong><br>
        <span style="font-size: 15px;">Reading Data<br>
             {{ (intval($sesdata['position'])+1).' out of '. $sesdata['numberOfFiles'].' files uploaded' }}
        </span>
    </div>
    @endif
    
 @php    
    \ini_set('memory_limit','-1');
    \ini_set('max_execution_time', 0); 
    if (ob_get_level() == 0) ob_start();

    if(isset($path)){
        echo 'Please wait while we extract your excel data!. <br>';
        echo 'Source: '.$path.'<br>';
        echo 'File index : '.(intval($sesdata['position'])+1).'<br>';
    }
    ob_flush();
    flush();
    $i = 2;
    $success = 0;
    $errors = 0;
    $listError = [];    
    $fldValue = 0;    
    
    if(!$isDone){                                        
            $objReader = new \PHPExcel_Reader_Excel2007();            
            $objReader->setReadDataOnly(false);        
            $spreadsheet = $objReader->load($path);        
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();            
            for($index = 2 ; $index <= $highestRow; $index++){    
                $field = [] ;                
                switch($sesdata['option']){
                    case '1':
                        $table = 'tbl_noncomp_educ_';
                        $a = $sheet->getCell('A'.$index)->getValue(); // region
                        $b = $sheet->getCell('B'.$index)->getValue(); // province
                        $c = $sheet->getCell('C'.$index)->getValue(); // city
                        $d = $sheet->getCell('D'.$index)->getValue(); // brgy
                        $e = $sheet->getCell('E'.$index)->getValue(); // psgc                
                        $f = $sheet->getCell('F'.$index)->getValue(); // hh_status
                        $g = $sheet->getCell('G'.$index)->getValue(); // hh_id
                        $h = $sheet->getCell('H'.$index)->getValue(); // entry_id
                        $i = $sheet->getCell('I'.$index)->getValue(); // lastname
                        $j = $sheet->getCell('J'.$index)->getValue(); // firstname
                        $k = $sheet->getCell('K'.$index)->getValue(); // midname
                        $l = $sheet->getCell('L'.$index)->getValue(); // ext                    
                        $m = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("M{$index}")->getValue())->format("Y-m-d");                    
                        $n = $sheet->getCell('N'.$index)->getValue(); // ip
                        $o = $sheet->getCell('O'.$index)->getValue(); // grade
                        $p = $sheet->getCell('P'.$index)->getValue(); // school id
                        $q = $sheet->getCell('Q'.$index)->getValue(); // school name
                        $r = $sheet->getCell('R'.$index)->getValue(); // school region
                        $s = $sheet->getCell('S'.$index)->getValue(); // school province
                        $t = $sheet->getCell('T'.$index)->getValue(); // school municipality
                        $u = $sheet->getCell('U'.$index)->getValue(); // school barangay
                        $v = $sheet->getCell('V'.$index)->getValue(); // DOM school id
                        $w = $sheet->getCell('W'.$index)->getValue(); // DOM school name
                        $x = $sheet->getCell('X'.$index)->getValue(); // DOM school region
                        $y = $sheet->getCell('Y'.$index)->getValue(); // DOM school province
                        $z = $sheet->getCell('Z'.$index)->getValue(); // DOM school municipality
                        $aa = $sheet->getCell('AA'.$index)->getValue(); // DOM school barangay 
                        $ab = $sheet->getCell('AB'.$index)->getValue(); // Remarks
                        $ac = $sheet->getCell('AC'.$index)->getValue(); // Month
                        $ad = '';                        
                        $ae = $sesdata['year']; // Year
                        $af = $sesdata['period']; // Period
                        $ag = date('Y-m-d H:i:s'); // date upload
                        $ah = $e;
                        
                        $a = utf8_encode($a);
                        $b = utf8_encode($b);
                        $c = utf8_encode($c);
                        $d = utf8_encode($d);
                        $i = utf8_encode($i);
                        $j = utf8_encode($j);
                        $k = utf8_encode($k);
                        $k = ($k!='' AND $k!=null)?utf8_encode($k):'';
                        $l = ($l!='' AND $l!=null)?$l:'';
                        $q = utf8_encode($q);
                        $r = utf8_encode($r);
                        $s = utf8_encode($s);
                        $t = utf8_encode($t);
                        $u = utf8_encode($u);                
                        $w = utf8_encode($w);
                        $x = utf8_encode($x);
                        $y = utf8_encode($y);
                        $z = utf8_encode($z);
                        $aa = utf8_encode($aa);   
                        $param = [null,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u,$v,$w,$x,$y,$z,$aa,$ab,$ac,$ad,$ae,$af,$ag,$ah];                        
                        $fldValue = 35;
                        $message = $g.' - '.$h.' - '.$i.' - '.$j.' - '.$k;
                    break;  
                    case '2':
                        $table = 'tbl_noncomp_health_';                        
                        $a = $sheet->getCell('A'.$index)->getValue(); // region
                        $b = $sheet->getCell('B'.$index)->getValue(); // province
                        $c = $sheet->getCell('C'.$index)->getValue(); // city
                        $d = $sheet->getCell('D'.$index)->getValue(); // brgy
                        $e = $sheet->getCell('E'.$index)->getValue(); // psgc                             
                        $f = $sheet->getCell('F'.$index)->getValue(); // hh_id
                        $g = $sheet->getCell('G'.$index)->getValue(); // entry_id                        
                        $h = $sheet->getCell('H'.$index)->getValue(); // hh_status                                              
                        $i = $sheet->getCell('I'.$index)->getValue(); // lastname
                        $j = $sheet->getCell('J'.$index)->getValue(); // firstname
                        $k = $sheet->getCell('K'.$index)->getValue(); // midname
                        $l = $sheet->getCell('L'.$index)->getValue(); // ext   
                        $m = $sheet->getCell('M'.$index)->getValue(); // sex                           
                        $n = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("N{$index}")->getValue())->format("Y-m-d");                        
                        $o = $sheet->getCell('O'.$index)->getValue(); // ip                        
                        $p = $sheet->getCell('P'.$index)->getValue(); // pregnant    
                        $q = $sheet->getCell('Q'.$index)->getValue(); // child                            
                        $r = $sheet->getCell('R'.$index)->getValue(); // health id
                        $s = $sheet->getCell('S'.$index)->getValue(); // health name
                        $t = $sheet->getCell('T'.$index)->getValue(); // health region
                        $u = $sheet->getCell('U'.$index)->getValue(); // health province
                        $v = $sheet->getCell('V'.$index)->getValue(); // health municipality
                        $w = $sheet->getCell('W'.$index)->getValue(); // health barangay                        
                        $x = $sheet->getCell('X'.$index)->getValue(); // DOM health id
                        $y = $sheet->getCell('Y'.$index)->getValue(); // DOM health name
                        $z = $sheet->getCell('Z'.$index)->getValue(); // DOM health region
                        $aa = $sheet->getCell('AA'.$index)->getValue(); // DOM health province
                        $ab = $sheet->getCell('AB'.$index)->getValue(); // DOM health municipality
                        $ac = $sheet->getCell('AC'.$index)->getValue(); // DOM health barangay                         
                        $ad = $sheet->getCell('AD'.$index)->getValue(); // Remarks
                        $ae = $sheet->getCell('AE'.$index)->getValue(); // Month                        
                        $af = '';                        
                        $ag = $sesdata['year']; // Year
                        $ah = $sesdata['period']; // Period
                        $ai = date('Y-m-d H:i:s'); // date upload
                        $aj = $e;
                        
                        $a = utf8_encode($a);
                        $b = utf8_encode($b);
                        $c = utf8_encode($c);
                        $d = utf8_encode($d);
                        $i = utf8_encode($i);
                        $j = utf8_encode($j);
                        $k = utf8_encode($k);
                        $k = ($k!='' AND $k!=null)?utf8_encode($k):'';
                        $l = ($l!='' AND $l!=null)?$l:'';
                        $q = utf8_encode($q);                        
                        $s = utf8_encode($s);
                        $t = utf8_encode($t);
                        $u = utf8_encode($u);                
                        $v = utf8_encode($v);                
                        $w = utf8_encode($w);                        
                        $y = utf8_encode($y);
                        $z = utf8_encode($z);
                        $aa = utf8_encode($aa);   
                        $ab = utf8_encode($ab);   
                        $ac = utf8_encode($ac);   
                        $param = [null,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u,$v,$w,$x,$y,$z,$aa,$ab,$ac,$ad,$ae,$af,$ag,$ah,$ai,$aj];                            
                        $fldValue = 37;
                        $message = $g.' - '.$h.' - '.$i.' - '.$j.' - '.$k;                    
                    break;
                    case '3':
                        $table = 'tbl_noncomp_fds_';   
                        $a = $sheet->getCell('A'.$index)->getValue(); // region
                        $b = $sheet->getCell('B'.$index)->getValue(); // province
                        $c = $sheet->getCell('C'.$index)->getValue(); // city
                        $d = $sheet->getCell('D'.$index)->getValue(); // brgy
                        $e = $sheet->getCell('E'.$index)->getValue(); // psgc                
                        $f = $sheet->getCell('F'.$index)->getValue(); // hh_id
                        $g = $sheet->getCell('G'.$index)->getValue(); // entry_id
                        $h = $sheet->getCell('H'.$index)->getValue(); // hh status
                        $i = $sheet->getCell('I'.$index)->getValue(); // lastname
                        $j = $sheet->getCell('J'.$index)->getValue(); // firstname
                        $k = $sheet->getCell('K'.$index)->getValue(); // midname
                        $l = $sheet->getCell('L'.$index)->getValue(); // ext   
                        $m = $sheet->getCell('M'.$index)->getValue(); // grade                       
                        $n = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("N{$index}")->getValue())->format("Y-m-d");                    
                        $o = $sheet->getCell('O'.$index)->getValue(); // ip                        
                        $p = $sheet->getCell('P'.$index)->getValue(); // Month
                        $q = '';                        
                        $r = $sesdata['year']; // Year
                        $s = $sesdata['period']; // Period
                        $t = date('Y-m-d H:i:s'); // date upload  
                        $u = $e;                                              

                        $a = utf8_encode($a);
                        $b = utf8_encode($b);
                        $c = utf8_encode($c);
                        $d = utf8_encode($d);                                                
                        $i = utf8_encode($i);
                        $j = utf8_encode($j);
                        $k = ($k!='' AND $k!=null)?utf8_encode($k):'';
                        $l = ($l!='' AND $l!=null)?$l:'';
                        
                        $param = [null,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u];                        
                        $fldValue = 22;
                        $message = $f.' - '.$g.' - '.$i.' - '.$j.' - '.$k;
                    break;
                                                                                                
                }         
                $percentage = round( $index / $highestRow * 100, 0 );
                try{                         
                    for($fld = 0 ; $fld < $fldValue; $fld++ ){ $field[] = '?'; }                    
                    DB::insert('insert into '.$table.$sesdata['year'].' values('.implode(',',$field).')',$param);                
                    echo '[ ' . $index . ' ] - Data successfully inserted!. '.$message.'<br>';                    
                    $success++;
                }catch(\Illuminate\Database\QueryException $ex){                        
                    $listError[] = $ex->getMessage();
                    echo '<span style="color:red">[ ' . $index . ' ] (3)- Error on ' . $table.$sesdata['year'].'_'.$sesdata['period'] . ' :  ' . $ex->getMessage() . '. </span><br>';
                    $errors++;
                }          
                echo '<script type="text/javascript">document.getElementById("divPercentage").innerHTML = "'.$percentage.'%"; </script>';
                ob_flush();
                flush();
            }              
            ob_end_flush();                      
        
        echo '<br>------------------------------------------------------------------------------------------------------------------<br>        
        <strong>Success : '.$success.' rows affected</strong><br>
        <strong>Other Errors Found : '.$errors.' rows affected</strong><br><br>
        <strong>Please Wait while we are reading the next file!.</strong><br>';  
        
        $detect = 0;
        if($sesdata['position']<$sesdata['numberOfFiles']){
            $detect = 1;
            $count = intval($sesdata['position']);                 
            switch(strtolower($extention)){
                case 'xlsx':
                    $sesdata['position'] = $count+1;
                break;
                case 'csv':                                        
                    if($isEof==false){
                        $sesdata['currentRow'] = 0;
                        $sesdata['position'] = $count+1;
                    }else{                        
                        $sesdata['currentRow'] = $index;
                    }                    
                break;                                                                        
            }                                                            
            if($sesdata['position']>$sesdata['numberOfFiles']) $detect = 0;            
        }        
        if(!$detect){ // no files to read            
            \Session::forget('uploadbasefilenoncom');
            echo '<script type="text/javascript">parent.enabledUploadFile();</script>'; 
        }else{ // if the list file is not done            
            \Session::put('uploadbasefilenoncom',$sesdata);
            if(!$errors){
                echo '<script type="text/javascript">setTimeout(function(){parent.jsRender(); },1000); </script>';
            }else{
                echo '<a href="#click" onclick="parent.jsRender();">Next</a>';
                echo '<script type="text/javascript">window.scrollTo(0,document.body.scrollHeight); stopInterval();</script>';
            }                        
        }    
    }else{         
        $time_end = microtime(true);                
        $execution_time = ($time_end - $sesdata['startTime'])/60;
        echo '<strong>No of Files Uploaded : '.$sesdata['numberOfFiles'].'</strong><br>';
        echo '<strong>Time Executed: '.$execution_time.'</strong><br>';
        echo '<h3><strong>Successfully Done!</strong></h3>'; 
        echo '<script type="text/javascript">parent.enabledUploadFile();</script>'; 
        \Session::forget('uploadbasefilenoncom');
    }
 @endphp     
</body>
</html>
        