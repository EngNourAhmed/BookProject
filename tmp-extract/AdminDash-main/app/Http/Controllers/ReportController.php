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
    public function store(Request $request, $article)
    {
        // الحصول على المقال
        $article = Article::findOrFail($article);
        $reportedUser = $article->user;
        $reporter = auth()->user();

        // إنشاء البلاغ
        $report = Report::create([
            'reporter_id' => $reporter->id,
            'reported_id' => $reportedUser->id,
            'article_id'  => $article->id,
            'reason'      => $request->reason,
            'status'      => 'reviewing',
        ]);

        // لو الطلب API → نرجّع JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال البلاغ بنجاح وسيتم مراجعته من قبل الإدارة.',
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

        // لو Web → redirect أو view
        return redirect()
            ->back()
            ->with('success', 'تم إرسال البلاغ بنجاح وسيتم مراجعته من قبل الإدارة.');
    }


    /**
     * عرض قائمة البلاغات
     */
    public function index(Request $request)
    {
        $reports = Report::with(['reporter', 'reported', 'article'])
            ->latest()
            ->paginate(20);

        if ($this->isApi($request)) {
            return response()->json($reports);
        }

        return view('reports.index', compact('reports'));
    }



    /**
     * تحديث حالة البلاغ
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:reviewing,resolved,blocked',
        ]);

        $report = Report::findOrFail($id);

        // لو البلاغ اتحول لـ BLOCKED
        if ($request->status === 'blocked') {
            // حذف المقال المرتبط
            if ($report->article_id) {
                $article = Article::find($report->article_id);

                if ($article) {
                    $article->delete();   // حذف المقال
                }
            }
        }

        // تحديث حالة البلاغ نفسها
        $report->update([
            'status' => $request->status
        ]);

        // API Response
        if ($this->isApi($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة البلاغ وحذف المقال المحظور عند الحاجة.'
            ]);
        }

        // Web Response
        return redirect()->back()->with(
            'success',
            'تم تحديث حالة البلاغ وحذف المقال المحظور عند الحاجة.'
        );
    }
}
