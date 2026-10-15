<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhaseSchedule;
use App\Models\FypPhase;
use App\Models\FypGroup;
use App\Models\ScheduleEvaluator;
use Illuminate\Http\Request;

class PhaseScheduleController extends Controller
{
    public function index()
    {
        $schedules = PhaseSchedule::with([
            'phase', 'group.department', 'group.session',
            'panel', 'evaluators.evaluator'
        ])->orderBy('schedule_date')->get()
        ->map(function ($s) {
            return [
                'id'            => $s->id,
                'phase'         => $s->phase?->name,
                'group_code'    => $s->group?->group_code,
                'department'    => $s->group?->department?->name,
                'session'       => $s->group?->session?->name,
                'panel'         => $s->panel?->name,
                'schedule_date' => $s->schedule_date,
                'schedule_time' => $s->schedule_time,
                'room'          => $s->room,
                'status'        => $s->status,
                'evaluators'    => $s->evaluators->map(fn($e) => [
                    'id'   => $e->evaluator?->id,
                    'name' => $e->evaluator?->name,
                ]),
            ];
        });

        return response()->json(['success' => true, 'data' => $schedules]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phase_id'        => 'required|exists:fyp_phases,id',
            'group_id'        => 'required|exists:fyp_groups,id',
            'schedule_date'   => 'required|date',
            'schedule_time'   => 'required',
            'evaluator_ids'   => 'required|array|min:1',
            'evaluator_ids.*' => 'exists:users,id',
        ]);

        $schedule = PhaseSchedule::create([
            'phase_id'      => $request->phase_id,
            'group_id'      => $request->group_id,
            'panel_id'      => $request->panel_id,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'room'          => $request->room,
            'status'        => 'scheduled',
        ]);

        foreach ($request->evaluator_ids as $evalId) {
            ScheduleEvaluator::create([
                'phase_schedule_id' => $schedule->id,
                'evaluator_id'      => $evalId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Schedule created!',
            'data'    => $schedule
        ], 201);
    }

    public function destroy(PhaseSchedule $schedule)
    {
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Schedule deleted!']);
    }
}