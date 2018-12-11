<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\APIS\QzDetailsController;

class QzExercise extends Model
{
    public function forceExecute()
    {
        $QzDetails = new QzDetailsController();
        $doExecute = $QzDetails->forceExecute();
        return $doExecute;
    }
}
