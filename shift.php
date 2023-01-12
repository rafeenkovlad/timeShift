<?php

class Shift 
{
	 /**
     * @throws Exception
     */
    public function timeZoneOffsetShiftBack($timestamp, $offset)
    {
        if (is_null($timestamp)) {
            return null;
        }
        if ($timestamp === '') {
            return '';
        }

        if (is_int($timestamp)) {
            $timestamp -= $offset;
            return (int)$timestamp;
        }

        if (is_string($timestamp) && preg_match('/^((\d{2,4}\S*?)(?2){1,2}[\sT]{1,2}(\d{2,4}\S*?)(?3){1,2})$/s', $timestamp)) {
            if (preg_match('/.+T/s', $timestamp)) {
                $foramt = 'c';
            }

            if (!isset($foramt) && preg_match('/^\d{2}:.*/s', $timestamp)) {
                $foramt = 'H:i:s d.m.Y';
            }

            if (!isset($foramt) && preg_match('/^\d{2}\..*/s', $timestamp)) {
                $foramt = 'd.m.Y H:i:s';
            }

            if (!isset($foramt)) {
                throw new RuntimeException('Формат не обнаружен.');
            }

            try {
                if ($foramt === 'd.m.Y H:i:s') {
                    $date = preg_replace('/((\d{2,4}\.*?)(?2){2}).*/s', '$1', $timestamp);
                    $time = preg_replace('/.*?\s((\d{2}\:*)(?2){1,2})/s', '$1', $timestamp);
                    if (preg_match('/\A(\d{2}\.\d{2}\.(\d{2}))\Z/s', $date)) {
                        $date = preg_replace('/\A(\d{2}\.\d{2}\.)(\d{2})/s', '${1}20$2', $date);
                    }
                    $date = (new DateTime($date))->format('d.m.Y');
                    $time = (new DateTime($time))->format('H:i:s');
                    $timestamp = $time . ' ' . $date;

                }
                $timestamp = (new DateTime($timestamp))->getTimestamp() - $offset;
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage() . ' функция timeZoneOffsetShiftBack.');
            }
            return (new DateTime())->setTimestamp($timestamp)->format($foramt);
        }

        if ($timestamp instanceof DateTime) {
            $timestamp = $timestamp->getTimestamp() + $offset;
            return (new DateTime())->setTimestamp($timestamp);
        }
        return $timestamp;
    }

    /**
     * @throws Exception
     */
    public function generateTimeOfTimezoneBack(array &$dateWithKey = [], array $dateOnlyKey = [], $offset = 0): void
    {
        foreach ($dateWithKey as &$one) {
            foreach ($dateOnlyKey as $specificField) {
                if (isset($one[$specificField])) {
                    $one[$specificField] = $this->timeZoneOffsetShiftBack($one[$specificField], $offset);
                }
            }
        }
        unset($one);
    }

    public function timeZoneOffsetShiftForward($timestamp, int $offset)
    {
        if (is_null($timestamp)) {
            return null;
        }
        if ($timestamp === '') {
            return '';
        }

        if (is_int($timestamp)) {
            $timestamp += $offset;
            return (int)$timestamp;
        }

        if (is_string($timestamp) && preg_match('/^((\d{2,4}\S*?)(?2){1,2}[\sT]{1,2}(\d{2,4}\S*?)(?3){1,2})$/s', $timestamp)) {
            if (preg_match('/.+T/s', $timestamp)) {
                $foramt = 'c';
            }

            if (!isset($foramt) && preg_match('/^\d{2}:.*/s', $timestamp)) {
                $foramt = 'H:i:s d.m.Y';
            }

            if (!isset($foramt) && preg_match('/^\d{2}\..*/s', $timestamp)) {
                $foramt = 'd.m.Y H:i:s';
            }

            if (!isset($foramt)) {
                throw new RuntimeException('Формат не обнаружен.');
            }

            try {
                if ($foramt === 'd.m.Y H:i:s') {
                    $date = preg_replace('/((\d{2,4}\.*?)(?2){2}).*/s', '$1', $timestamp);
                    $time = preg_replace('/.*?\s((\d{2}\:*)(?2){1,2})/s', '$1', $timestamp);
                    if (preg_match('/\A(\d{2}\.\d{2}\.(\d{2}))\Z/s', $date)) {
                        $date = preg_replace('/\A(\d{2}\.\d{2}\.(\d{2}))/s', '${1}20$2', $date);
                    }
                    $date = (new DateTime($date))->format('d.m.Y');
                    $time = (new DateTime($time))->format('H:i:s');
                    $timestamp = $time . ' ' . $date;
                }
                $timestamp = (new DateTime($timestamp))->getTimestamp() + $offset;
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage() . ' функция timeZoneOffsetShiftForward.');
            }
            return (new DateTime())->setTimestamp($timestamp)->format($foramt);
        }

        if ($timestamp instanceof DateTime) {
            $timestamp = $timestamp->getTimestamp() + $offset;
            return (new DateTime())->setTimestamp($timestamp);
        }
        return $timestamp;
    }

    public function generateTimeOfTimezoneForward(array &$dateWithKey = [], array $dateOnlyKey = [], $offset = 0): void
    {
        foreach ($dateWithKey as &$one) {
            foreach ($dateOnlyKey as $specificField) {
                if (isset($one[$specificField])) {
                    $one[$specificField] = $this->timeZoneOffsetShiftForward($one[$specificField], $offset);
                }
            }
        }
        unset($one);
    }
	
}