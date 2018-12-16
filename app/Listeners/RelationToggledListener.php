<?php

namespace App\Listeners;

use App\Activity;
use App\Notifications\LikedMyThread;
use App\Notifications\NewFollower;
use Overtrue\LaravelFollow\Events\RelationToggled;

class RelationToggledListener
{
    /**
     * Handle the event.
     *
     * @param  RelationToggled  $event
     * @return void
     */
    public function handle(RelationToggled $event)
    {
        $targetType = \strtolower(\class_basename($event->class));
        $relation = $event->getRelationType();

        $event->getTargetsCollection()->map(function($target) use ($event, $relation, $targetType) {
            $logName = $relation.'.'.$targetType;
            if (\in_array($target->id, $event->attached)) {
                $properties = [];
                if ($targetType == 'thread') {
                    $properties['content'] = $target->content->activity_log_content;
                }

                \activity($logName)
                    ->performedOn($target)
                    ->withProperties($properties)
                    ->log('create relationship');

            } else if (\in_array($target->id, $event->detached)) {

                Activity::whereSubjectType(\get_class($target))
                    ->whereSubjectId($target->id)
                    ->where('log_name', $logName)
                    ->where('created_at', '>=', \today())
                    ->delete();
            }

            $event->causer->refreshCache();

            if (method_exists($target, 'refreshCache')) {
                $target->refreshCache();
            }

            switch ($targetType) {
                case 'thread':
                    if ($relation == 'like') {
                        $target->user->notify(new LikedMyThread($target, $event->causer));
                    }
                    break;
                case 'node':
                    break;
                case 'user':
                    if ($relation == 'follow') {
                        $target->notify(new NewFollower($event->causer));
                    }
            }
        });
    }
}
