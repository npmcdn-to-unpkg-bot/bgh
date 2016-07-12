<?php
namespace App\Http\Controllers\Admin\Report;

use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class ReportController extends Controller
{
    /**
     * @return $this
     */
    public function getReports()
    {
        $title = 'Reports';

        return view('admin.report.index', compact('title'));
    }

    /**
     * @param $id
     * @return $this
     */
    public function getReadReport($id)
    {
        $report = Report::with('user')->findOrFail($id);
        $report->solved = '1';
        $report->save();

        return view('admin.report.read')
            ->with('title', 'Full Report')
            ->with('report', $report);
    }


    public function getData()
    {
        $reports = Report::with('user')->orderBy('created_at', 'desc')->get();


        $datatables = app('datatables')->of($reports);


        return $datatables->addColumn('type', function ($report) {
            if ($report->type == 'user') {
                return link_to_route('user', 'User', [$report->report]);
            }
            if ($report->type == 'image') {
                return link_to_route('image', 'Image', [$report->report]);
            }
        })->editColumn('report', function ($report) {
            if ($report->type == 'user') {
                return link_to_route('user', $report->report, [$report->report]);
            }
            if ($report->type == 'image') {
                return link_to_route('image', $report->report, [$report->report]);
            }
        })->addColumn('status', function ($report) {
            if ($report->solved == 1) {
                return 'Checked';
            } else {
                return 'Unchecked';
            }
        })->editColumn('user_id', function ($report) {
            return link_to_route('user', $report->user->username, [$report->user->username]);
        })->editColumn('read_report', function ($report) {
            return link_to_route('admin.reports.read', 'Read Full Report', [$report->id]);
        })->make(true);

    }
}
