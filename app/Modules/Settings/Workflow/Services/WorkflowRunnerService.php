<?php

namespace App\Modules\Settings\Workflow\Services;

use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Workflow\Models\Workflow;
use App\Notifications\WorkflowNotification;

class WorkflowRunnerService
{
    public function run(string $event, array $payload = [])
    {
        $workflows = Workflow::query()
            ->where('event', $event)
            ->where('is_active', true)
            ->with('actions')
            ->get();

        $results = [];

        foreach ($workflows as $workflow) {
            $rules = NotificationRule::query()
                ->where('workflow_id', $workflow->id)
                ->where('is_enabled', true)
                ->with([
                    'recipients',
                    'channels',
                ])
                ->get();

            foreach ($rules as $rule) {
                foreach ($rule->channels as $channel) {
                    if (!$channel->is_enabled) {
                        continue;
                    }

                    if ($channel->channel !== 'email') {
                        continue;
                    }

                    foreach ($rule->recipients as $recipient) {
                        $notifiable = $this->resolveRecipient(
                            $recipient->type,
                            $recipient->value,
                            $payload
                        );

                        if ($notifiable) {
                            $notifiable->notify(
                                new WorkflowNotification(
                                    $rule->message,
                                    $rule->name
                                )
                            );
                        }
                    }
                }

                $results[] = [
                    'workflow' => $workflow,
                    'rule' => $rule,
                    'payload' => $payload,
                ];
            }
        }

        return $results;
    }

    private function resolveRecipient(
        string $type,
        string $value,
        array $payload
    ) {
        if (
            $type === 'manager' &&
            $value === 'deal_owner_manager'
        ) {
            $deal = $payload['deal'] ?? null;

            if (!$deal) {
                return null;
            }

            $deal->loadMissing('owner.manager');

            return $deal->owner?->manager;
        }

        return null;
    }
}