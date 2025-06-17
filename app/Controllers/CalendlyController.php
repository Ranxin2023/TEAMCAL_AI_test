<?php

namespace App\Controllers;

class CalendlyController extends BaseController
{
    public function fetchAvailability()
    {
        $request = service('request');
        $inputUrl = urldecode(trim($request->getPost('calendly_link')));

        if (!filter_var($inputUrl, FILTER_VALIDATE_URL)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid Calendly link provided.'
            ]);
        }

        // Hardcoded for your example
        $eventTypeId = env('CALENDLY_EVENT_TYPE_ID');
        $schedulingUuid = env('CALENDLY_SCHEDULING_UUID');
        $timezone = env('CALENDLY_TIMEZONE');

        // Extract month or date from link
        $parsedUrl = parse_url($inputUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        $now = new \DateTimeImmutable();
        $rangeStart = null;

        if (isset($queryParams['date'])) {
            // Exact date is provided
            $dateProvided = new \DateTimeImmutable($queryParams['date']);
            if ($dateProvided < $now) {
                $rangeStart = $now->format('Y-m-d');
            } else {
                $rangeStart = $dateProvided->format('Y-m-d');
            }
        } elseif (isset($queryParams['month'])) {
            // Only month is provided
            try {
                $monthProvided = new \DateTimeImmutable($queryParams['month'] . '-01');
                if ($monthProvided < $now->modify('first day of this month')) {
                    $rangeStart = $now->format('Y-m-d');
                } elseif ($monthProvided->format('Y-m') === $now->format('Y-m')) {
                    $rangeStart = $now->format('Y-m-d');
                } else {
                    $rangeStart = $monthProvided->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $rangeStart = $now->format('Y-m-d');
            }
        } else {
            // Default to today
            $rangeStart = $now->format('Y-m-d');
        }

        // Always +4 weeks
        $rangeEnd = (new \DateTimeImmutable($rangeStart))->modify('+4 weeks')->format('Y-m-d');

        $apiUrl = "https://calendly.com/api/booking/event_types/{$eventTypeId}/calendar/range"
                . "?timezone=" . urlencode($timezone)
                . "&diagnostics=false"
                . "&range_start={$rangeStart}"
                . "&range_end={$rangeEnd}"
                . "&scheduling_link_uuid={$schedulingUuid}";

        // Fetch API data
        $contextOptions = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0\r\nAccept: application/json\r\n"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        $context = stream_context_create($contextOptions);
        $json = @file_get_contents($apiUrl, false, $context);

        if ($json === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unable to fetch Calendly API data.'
            ]);
        }

        $data = json_decode($json, true);

        if (!isset($data['days']) || empty($data['days'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No availability data found.'
            ]);
        }

        $slots = [];
        foreach ($data['days'] as $day) {
            if (isset($day['spots']) && is_array($day['spots'])) {
                foreach ($day['spots'] as $spot) {
                    if ($spot['status'] === 'available') {
                        $slots[] = [
                            'date' => $day['date'],
                            'start_time' => $spot['start_time'],
                            'invitees_remaining' => $spot['invitees_remaining']
                        ];
                    }
                }
            }
        }

        if (empty($slots)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No available slots found.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'slots' => $slots
        ]);
    }
}
