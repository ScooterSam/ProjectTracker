<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use function str_random;

class ProjectController extends Controller
{
    public function publicView($uid)
    {
        $project = Project::where('uid', $uid)->first();

        if (!$project) {
            abort(404);
        }

        $hoursThisMonth = "0";
        $hoursThisWeek  = "0";
        $totalHours     = "0";

        if ($project->times()->first()) {
            $hoursThisMonth = $project->hoursBetween(
                now()->startOfMonth(),
                now()->endOfMonth()
            );
            $hoursThisWeek  = $project->hoursBetween(
                now()->startOfWeek(),
                now()->endOfWeek()
            );
            $totalHours     = $project->totalHours();

        }

        return view('project.public-view', [
            'project'        => $project,
            'times'          => $project->times()->orderBy('date', 'desc')->paginate(30),
            'hoursThisMonth' => $hoursThisMonth,
            'hoursThisWeek'  => $hoursThisWeek,
            'totalHours'     => $totalHours,
        ]);
    }

    public function view(Project $project)
    {
        $hoursThisMonth = "0";
        $hoursThisWeek  = "0";
        $totalHours     = "0";

        if ($project->times()->first()) {
            $hoursThisMonth = $project->hoursBetween(
                now()->startOfMonth(),
                now()->endOfMonth()
            );
            $hoursThisWeek  = $project->hoursBetween(
                now()->startOfWeek(),
                now()->endOfWeek()
            );
            $totalHours     = $project->totalHours();

        }

        return view('project.view', [
            'project'        => $project,
            'times'          => $project->times()->orderBy('date', 'desc')->paginate(30),
            'hoursThisMonth' => $hoursThisMonth,
            'hoursThisWeek'  => $hoursThisWeek,
            'totalHours'     => $totalHours,
        ]);
    }

    public function create()
    {
        $project          = new Project;
        $project->uid     = str_random();
        $project->title   = request('title');
        $project->user_id = auth()->id();
        $project->save();

        return redirect()->route('home')
            ->with('success', 'Successfully created project.');
    }
}
