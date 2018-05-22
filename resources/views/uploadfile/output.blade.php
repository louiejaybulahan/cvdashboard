@php
use Illuminate\Support\Facades\Session; 
@endphp
<html>
    <head>
        <title>Uploading baseline data</title>
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
    \ini_set("memory_limit",-1);
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
    $table = 'tbl_turnout_';
    if(!$isDone){            
        if(file_exists($path)){                                     
            $fldValue = 33;
            for($fld = 0 ; $fld < $fldValue; $fld++ ){ $field[] = '?'; }
            echo $extention;
            if($extention=='xlsx'){                
                $objReader = new \PHPExcel_Reader_Excel2007();            
                $objReader->setReadDataOnly(true);        
                $spreadsheet = $objReader->load($path);        
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();
                for($index = 2 ; $index <= $highestRow; $index++){           
                    $a = $sheet->getCell('A'.$index)->getValue(); // region
                    $b = $sheet->getCell('B'.$index)->getValue(); // province
                    $c = $sheet->getCell('C'.$index)->getValue(); // city
                    $d = $sheet->getCell('D'.$index)->getValue(); // psgc
                    $e = $sheet->getCell('E'.$index)->getValue(); // brgy
                    $f = $sheet->getCell('F'.$index)->getValue(); // category
                    $g = $sheet->getCell('G'.$index)->getValue(); // set
                    $h = $sheet->getCell('H'.$index)->getValue(); // group
                    $i = $sheet->getCell('I'.$index)->getValue(); // eligible
                    $j = $sheet->getCell('J'.$index)->getValue(); // not attending dominant
                    $k = $sheet->getCell('K'.$index)->getValue(); // attending dominant
                    $l = $sheet->getCell('L'.$index)->getValue(); // attending deleted dominant
                    $m = $sheet->getCell('M'.$index)->getValue(); // outside
                    $n = $sheet->getCell('N'.$index)->getValue(); // monitored under dominant
                    $o = $sheet->getCell('O'.$index)->getValue(); // encoded and apprpoved
                    $p = $sheet->getCell('P'.$index)->getValue(); // submitted with deworming conducted
                    $q = $sheet->getCell('Q'.$index)->getValue(); // not encoded or approved
                    $r = $sheet->getCell('R'.$index)->getValue(); // encoded under forve majeure
                    $s = $sheet->getCell('S'.$index)->getValue(); // non-compliat
                    $t = $sheet->getCell('T'.$index)->getValue(); // compliant
                    $u = $sheet->getCell('U'.$index)->getValue(); // remarks 1
                    $v = $sheet->getCell('V'.$index)->getValue(); // remarks 2
                    $w = $sheet->getCell('W'.$index)->getValue(); // remarks 3
                    $x = $sheet->getCell('X'.$index)->getValue(); // remarks 4
                    $y = $sheet->getCell('Y'.$index)->getValue(); // year
                    $z = $sheet->getCell('Z'.$index)->getValue(); // period
                    $aa = $sheet->getCell('AA'.$index)->getValue(); // month
                    $ab = $sheet->getCell('AB'.$index)->getValue(); // client status
                    $ac = $sheet->getCell('AC'.$index)->getValue(); // sex
                    $ad = $sheet->getCell('AD'.$index)->getValue(); // grade group
                    $ae = $sheet->getCell('AE'.$index)->getValue(); // ip                    
                    $ad = (($ad!='' AND $ad!=null)?$ad:'');

                    $a = utf8_encode($a);
                    $b = utf8_encode($b);
                    $c = utf8_encode($c);
                    $d = utf8_encode($d);
                    $e = utf8_encode($e);

                    $param = [null,$a,$b,$c,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u,$v,$w,$x,$y,$z,$aa,$ab,$ac,$ad,$ae,$d,$d];
                    try{                                                    
                        DB::insert('insert into '.$table.$sesdata['year'].'_'.$sesdata['period'].' values('.implode(',',$field).')',$param);                
                        echo '[ ' . $index . ' ] - Data successfully inserted!. '.$param[1].' - '.$param[2].' - '.$param[3].' - '.$param[4].'<br>';                                
                    }catch(\Illuminate\Database\QueryException $ex){                        
                        $listError[] = $ex->getMessage();
                        echo '<span style="color:red">[ ' . $index . ' ] (3)- Error on ' . $table. ' :  ' . $ex->getMessage() . '. </span><br>';                                    
                    }    
                    $percentage = round( $index / $highestRow * 100, 0 );
                    echo '<script type="text/javascript">document.getElementById("divPercentage").innerHTML = "'.$percentage.'%"; </script>';
                    ob_flush();
                    flush();
                }
            }else{            
                $file = new SplFileObject($path);
                $csv = array();               
                $limit = $sesdata['limit'];  
                $line  = intval($sesdata['currentRow']);
                $index = $sesdata['currentRow'];
                $pIndex = 0;
                $isEof = false;                     
                try{                
                    while(!$file->eof()){
                        if( $index < ($limit+$line) ){
                            $file->seek($index);                                 
                            $row = str_getcsv($file->current());                        
                            $index++;
                            $pIndex++;

                            $a = $row[0]; // region
                            $b = $row[1]; // province
                            $c = $row[2]; // city
                            $d = $row[3]; // psgc
                            $e = $row[4]; // brgy
                            $f = $row[5]; // category
                            $g = $row[6]; // set
                            $h = $row[7]; // group
                            $i = $row[8]; // eligible
                            $j = $row[9]; // not attending dominant
                            $k = $row[10]; // attending dominant
                            $l = $row[11]; // attending deleted dominant
                            $m = $row[12]; // outside
                            $n = $row[13]; // monitored under dominant
                            $o = $row[14]; // encoded and apprpoved
                            $p = $row[15]; // submitted with deworming conducted
                            $q = $row[16]; // not encoded or approved
                            $r = $row[17]; // encoded under forve majeure
                            $s = $row[18]; // non-compliat
                            $t = $row[19]; // compliant
                            $u = $row[20]; // remarks 1
                            $v = $row[21]; // remarks 2
                            $w = $row[22]; // remarks 3
                            $x = $row[23]; // remarks 4
                            $y = $row[24]; // year
                            $z = $row[25]; // period
                            $aa = $row[26]; // month
                            $ab = $row[27]; // client status
                            $ac = $row[28]; // sex
                            $ad = $row[29]; // grade group
                            $ae = $row[30]; // ip
                            $ad = (($ad!='' AND $ad!=null)?$ad:'');
                            
                            $a = utf8_encode($a);
                            $b = utf8_encode($b);
                            $c = utf8_encode($c);
                            $d = utf8_encode($d);
                            $e = utf8_encode($e);
                    
                            $param = [null,$a,$b,$c,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u,$v,$w,$x,$y,$z,$aa,$ab,$ac,$ad,$ae,$d,$d];
                            try{                                         
                                DB::insert('insert into '.$table.$sesdata['year'].'_'.$sesdata['period'].' values('.implode(',',$field).')',$param);                
                                echo '[ ' . $index . ' ] - Data successfully inserted!. '.$param[1].' - '.$param[2].' - '.$param[3].' - '.$param[4].'<br>';                                        
                                success++;
                                
                            }catch(\Illuminate\Database\QueryException $ex){                        
                                $errors++;
                                $listError[] = $ex->getMessage();
                                echo '<span style="color:red">[ ' . $index . ' ] (3)- Error on ' . $table. ' :  ' . $ex->getMessage() . '. </span><br>';                                            
                            }        
                            $percentage = round( $pIndex / $sesdata['limit'] * 100, 0 );
                            echo '<script type="text/javascript">document.getElementById("divPercentage").innerHTML = "'.$percentage.'%"; </script>';
                        }                      
                        if($file->fgets() == false){ $isEof = true; }
                        ob_flush();
                        flush();
                    }
                }catch(\Exception $e){}
            }                                    
            ob_end_flush();            
        }    

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
            \Session::forget('uploadbasefile');
            echo '<script type="text/javascript">parent.enabledUploadFile();</script>';             
        }else{ // if the list file is not done            
            \Session::put('uploadbasefile',$sesdata);
            if(!$errors){
                echo '<script type="text/javascript">setTimeout(function(){parent.jsRender(); },1000); </script>';                
                // echo '<a href="#click" onclick="parent.jsRender();">Next</a>';
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
        // \Session::forget('uploadbasefile');
    }
 @endphp
 
    
</body>
</html>
        