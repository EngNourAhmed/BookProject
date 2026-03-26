<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    private function isApi(Request $request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    /**
     * إنشاء بلاغ جديد من المستخدم
     */
    public function store(Request $request, $article = null)
    {
        // الحصول على المقال
        $articleId = $article ?? $request->input('article');
        $article = Article::findOrFail($articleId);
        $reportedUser = $article->user;
        $reporter = auth()->user();

        // إنشاء البلاغ
        $report = Report::create([
            'reporter_id' => $reporter->id,
            'reported_id' => $reportedUser->id,
            'article_id'  => $article->id,
            'reason'      => $request->reason,
            'description' => $request->description,
            'status'      => 'reviewing',
        ]);

        // Notify Admin
        $admin = User::find(11);
        if ($admin) {
            $admin->notify(new \App\Notifications\SystemNotification(
                'New Report Submitted',
                "User {$reporter->name} reported article: {$article->title}",
                ['report_id' => $report->id, 'article_id' => $article->id]
            ));
        }

        // If API request → return JSON
        if ($this->isApi($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully and will be reviewed by admin.',
                'data' => [
                    'report_id' => $report->id,
                    'reporter' => [
                        'id'    => $reporter->id,
                        'name'  => $reporter->name,
                        'email' => $reporter->email,
                    ],
                    'reported_user' => [
                        'id'    => $reportedUser->id,
                        'name'  => $reportedUser->name,
                        'email' => $reportedUser->email,
                    ],
                    'article_id' => $article->id,
                    'reason'     => $report->reason,
                    'status'     => $report->status,
                ],
            ], 201);
        }

        // If Web → redirect or view
        return redirect()
            ->back()
            ->with('success', 'Report submitted successfully and will be reviewed by admin.');
    }


    /**
     * Display a list of reports
     */
    public function index(Request $request)
    {
        $reports = Report::with(['reporter', 'reported', 'article'])
            ->latest()
            ->paginate(20);

        $articles = Article::select('id', 'title')->get();

        if ($this->isApi($request)) {
            return response()->json($reports);
        }

    return view('reports.index', compact('reports', 'articles'));
    }

    /**
     * Dedicated Resolution Center for chatting with writers of reported articles.
     */
    public function resolution(Request $request)
    {
        $reports = Report::with(['reporter', 'reported', 'article'])
            ->where('status', 'reviewing')
            ->latest()
            ->get();

        $activeReportId = $request->input('id');
        $activeReport = null;
        $activeConversation = null;

        if ($activeReportId) {
            $activeReport = Report::with(['reporter', 'reported', 'article'])->find($activeReportId);
            if ($activeReport && $activeReport->reported_id) {
                $adminId = 11;
                $user_one_id = min($adminId, $activeReport->reported_id);
                $user_two_id = max($adminId, $activeReport->reported_id);
                
                $activeConversation = \App\Models\Conversation::firstOrCreate([
                    'user_one_id' => $user_one_id,
                    'user_two_id' => $user_two_id
                ]);
            }
        }

        return view('reports.resolution', compact('reports', 'activeReport', 'activeConversation'));
    }



    /**
     * Update report status
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:reviewing,resolved,blocked',
        ]);

        $report = Report::findOrFail($id);

        // If the report status changed to BLOCKED
        if ($request->status === 'blocked') {
            // Delete the associated article
            if ($report->article_id) {
                $article = Article::find($report->article_id);

                if ($article) {
                    $article->delete();   // Delete the article
                }
            }
        }

        // Update the report status itself
        $report->update([
            'status' => $request->status
        ]);

        // API Response
        if ($this->isApi($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Report status updated and blocked content removed if necessary.'
            ]);
        }

        // Web Response
        return redirect()->back()->with(
            'success',
            'Report status updated and blocked content removed if necessary.'
        );
    }
}
