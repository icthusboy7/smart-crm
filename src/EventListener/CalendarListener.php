<?php
/**
 * Listener para la creación del calendario mediante FullCalendar JS
 */
namespace App\EventListener;

use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;

/**
 * Class CalendarListener
 * @package App\EventListener
 */
class CalendarListener
{
    /**
     * Cargar datos calendario
     * @param CalendarEvent $calendar
     */
    public function load(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // You may want to make a custom query to fill the calendar

        $calendar->addEvent(new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesdays this week')
        ));

        // If the end date is null or not defined, it creates a all day event
        $calendar->addEvent(new Event(
            'All day event',
            new \DateTime('Friday this week')
        ));
    }
}
