<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\APIS\QzDetailsController;
class QzRmb extends Model
{
    protected $table = 'qz_rmb';
    public $timestamps = false;
    
    public function Rmb(){
        $QzDetails = new QzDetailsController();
        $doExecute = $QzDetails->getRmb();
        return $doExecute;
    }
}
