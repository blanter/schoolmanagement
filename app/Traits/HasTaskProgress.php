<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\TeacherWeeklyPlan;
use App\Models\TeacherDailyDetail;
use App\Models\TeacherStudentProgress;
use App\Models\TeacherMonthlyEvaluation;
use App\Models\PemakmuranTeori;
use App\Models\PemakmuranCase;
use App\Models\PemakmuranProyek;
use App\Models\PemakmuranProblem;
use App\Models\PemakmuranCreative;
use App\Models\StudentLifebook;
use App\Models\TeacherResearchProject;

trait HasTaskProgress
{
    public function getPlannerProgress($month = null, $year = null)
    {
        if (!$month || !$year) {
            $p = Carbon::now()->subMonth();
            $month = $p->month;
            $year = $p->year;
        }

        $userId = $this->id;

        // --- 1. Teacher Planner (50%) ---
        $targetDate = Carbon::create($year, $month, 1);
        $startOfMonth = $targetDate->copy()->startOfMonth();
        $endOfMonth = $targetDate->copy()->endOfMonth();

        $totalWeekdays = 0;
        $filledWeekdays = 0;
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            if ($date->isWeekday()) {
                $totalWeekdays++;
                if (TeacherWeeklyPlan::where('user_id', $userId)->whereDate('tanggal', $date->toDateString())->exists()) {
                    $filledWeekdays++;
                }
            }
        }
        $weeklyProgress = $totalWeekdays > 0 ? ($filledWeekdays / $totalWeekdays) * 100 : 0;

        $hasDaily = TeacherDailyDetail::where('user_id', $userId)->where('year', $year)->where('month', $month)->whereNotNull('note')->where('note', '!=', '')->exists();
        $dailyProgress = $hasDaily ? 100 : 0;

        $hasStudentProgress = TeacherStudentProgress::where('user_id', $userId)->where('year', $year)->where('month', $month)->exists();
        $studentProgress = $hasStudentProgress ? 100 : 0;

        $hasEvaluation = TeacherMonthlyEvaluation::where('user_id', $userId)->where('year', $year)->where('month', $month)->exists();
        $evaluationProgress = $hasEvaluation ? 100 : 0;

        $teacherPlannerAvg = ($weeklyProgress + $dailyProgress + $studentProgress + $evaluationProgress) / 4;
        $teacherPlannerWeighted = $teacherPlannerAvg * 0.5;


        // --- 2. Teacher Planner Pemakmuran (20%) ---
        $pemakmuranModels = [
            PemakmuranTeori::class,
            PemakmuranCase::class,
            PemakmuranProyek::class,
            PemakmuranProblem::class,
            PemakmuranCreative::class
        ];
        $pemakmuranFilled = 0;
        foreach ($pemakmuranModels as $model) {
            if ($model::where('user_id', $userId)->where('year', $year)->where('month', $month)->whereNotNull('content')->where('content', '!=', '')->exists()) {
                $pemakmuranFilled++;
            }
        }
        $pemakmuranAvg = ($pemakmuranFilled / count($pemakmuranModels)) * 100;
        $pemakmuranWeighted = $pemakmuranAvg * 0.2;


        // --- 3. Student Lifebook (20%) ---
        $lifebookData = StudentLifebook::where('user_id', $userId)->where('year', $year)->where('month', $month)->first();
        $lifebookFilled = 0;
        if ($lifebookData) {
            $fields = ['goals_monthly', 'life_aspects', 'vision_yearly', 'vision_progress', 'gratitude'];
            foreach ($fields as $field) {
                if (!empty($lifebookData->$field)) {
                    $lifebookFilled++;
                }
            }
        }
        $lifebookAvg = ($lifebookFilled / 5) * 100;
        $lifebookWeighted = $lifebookAvg * 0.2;


        // --- 4. Karya Penelitian (10%) ---
        $baseYear = ($month <= 6) ? $year - 1 : $year;
        $semester = ($month <= 6) ? 2 : 1;

        $research = TeacherResearchProject::where('user_id', $userId)->where('year', $baseYear)->where('semester', $semester)->first();
        $researchChecked = 0;
        if ($research) {
            if ($research->judul_check)
                $researchChecked++;
            if ($research->rumusan_check)
                $researchChecked++;
            if ($research->penelitian_check)
                $researchChecked++;
            if ($research->kesimpulan_check)
                $researchChecked++;
        }
        $researchAvg = ($researchChecked / 4) * 100;
        $researchWeighted = $researchAvg * 0.1;

        $total = round($teacherPlannerWeighted + $pemakmuranWeighted + $lifebookWeighted + $researchWeighted);

        return [
            'total' => $total,
            'details' => [
                'Teacher Planner (50%)' => round($teacherPlannerAvg),
                'Pemakmuran (20%)' => round($pemakmuranAvg),
                'Student Lifebook (20%)' => round($lifebookAvg),
                'Research (10%)' => round($researchAvg),
            ]
        ];
    }
}
