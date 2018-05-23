<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Tbl_turnout extends Model
{   
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}