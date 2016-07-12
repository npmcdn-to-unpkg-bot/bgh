<?php
namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class ReportController extends Controller
{
    /**
     * @param $username
     * @return mixed
     */
    public function postReportUser(Request $request)
    {
        $this->validate($request, [
            'report'               => 'required|min:10|max:200',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);
        $report = new Report();
        $report->report = $request->route('username');
        $report->type = 'user';
        $report->user_id = auth()->user()->id;
        $report->description = $request->get('report');
        $report->save();

        return redirect()->route('gallery')->with('flashSuccess', 'Thanks, user is now reported we will take quick actions');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function postReportImage($id, Request $request)
    {
        $this->validate($request, [
            'report'               => 'required|min:10|max:200',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);

        $report = new Report();
        $report->report = $id;
        $report->user_id = auth()->user()->id;
        $report->type = 'image';
        $report->description = $request->get('report');
        $report->save();

        return redirect()->route('gallery')->with('flashSuccess', 'Thanks, Image is now reported we will take quick actions');
    }

    /**
     * @return mixed
     */
    public function getReport()
    {
        $title = t('Report');

        return view('report.index', compact('title'));
    }
}
