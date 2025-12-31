<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuspendedController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $suspendReason = null;
        $suspendReasonType = null;

        if ($user && $user->company_id) {
            $company = \App\Models\Company::find($user->company_id);

            if ($company && $company->suspended) {
                $suspendReason = $company->suspend_reason;
                $suspendReasonType = $company->suspend_reason_type;
            }
        }

        return view('subscription.suspended', compact('suspendReason', 'suspendReasonType'));
    }
}
