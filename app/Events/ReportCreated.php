<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use App\Models\Report;

class ReportCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels, Dispatchable;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('reports');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->report->id,
            'title' => $this->report->title,
            'type' => $this->report->type,
            'priority' => $this->report->priority,
            'status' => $this->report->status,
            'description' => $this->report->description,
            'photo' => $this->report->photo
                ? route('reports.photo.show', $this->report->id)
                : null,
        ];
    }
}
